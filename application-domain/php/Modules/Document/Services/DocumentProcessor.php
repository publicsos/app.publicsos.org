<?php

namespace Modules\Document\Services;

use Illuminate\Support\Collection;
use Modules\Document\Models\Document;

use Modules\Document\Models\DocumentEntity;
use Modules\Document\Saloon\Connectors\NLPConnector;
use Modules\Document\Saloon\Requests\NLP\ProcessDocument;
use Modules\Document\Contracts\DocumentProcessorContract;
use Modules\Document\Repositories\DocumentRepository;
use Modules\Document\Saloon\Connectors\MonitorulOficialConnector;
use Modules\Document\Saloon\Requests\Monitorul\GetPage;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Enums\DocumentStatus;
use Symfony\Component\DomCrawler\Crawler;

use Illuminate\Support\Str;

class DocumentProcessor implements DocumentProcessorContract
{
    public function __construct(
        private readonly DocumentRepository $repository,

    ) {}


    public function getDocuments(string $sessionID, string $date)
    {
        $content =  $this->getMonitorulOficialData($date, $sessionID);

        $crawler = new Crawler($content);

        $links = $crawler->filter('a.btn')->each(function (Crawler $node) {
            return [
                'href' => "https://monitoruloficial.ro". $node->filter('a')->attr('href'),
                'text' => $node->filter('a')->text(),
            ];
        });

        return $links;
    }


    private function getMonitorulOficialData($date, $cookie) {
        $url = 'https://monitoruloficial.ro/ramo_customs/emonitor/get_mo.php';
        $data = ['today' => $date];
        $headers = [
            'content-type: application/x-www-form-urlencoded; charset=UTF-8',
            'referer: https://monitoruloficial.ro/e-monitor/',
            'cookie: PHPSESSID=' . $cookie,
        ];

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); //Enable SSL verification.
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); //Verify host against certificate.
        curl_setopt($ch, CURLOPT_TIMEOUT, 1800); //Sets timeout to 1800 seconds(30 minutes).

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            return 'Curl error: ' . curl_error($ch);
        } else {
            return $response;
        }

        curl_close($ch);
    }

    public function process(Document $document)
    {

        $connector = new NLPConnector();

        $request = new ProcessDocument($document->source);

        $response = $connector->send($request);

        $entities = $response->dtoOrFail()->entities;

        $document->content = $response->dtoOrFail()->markdown;

        foreach ($entities as $entityData) {
            // Assuming $entityData is an array or object containing the entity attributes

            // Check if an entity with the same attributes already exists
            $existingEntity = DocumentEntity::where('document_id', $document->id)
                ->where('type', $entityData[0]) // Replace 'name' with the actual attribute name
                ->where('value', $entityData[1]) // Replace 'type' with the actual attribute name
                // Add other attributes for uniqueness check if necessary
                ->first();

            if (!$existingEntity) {
                // Entity doesn't exist, create it
                $document->entities()->create([
                    'type' => $entityData[0],
                    'value' => $entityData[1],
                    // Add other entity attributes here
                ]);
            }

        }

        $document->status = DocumentStatus::Processed->value;
        $document->save();

        $document->refresh();

        return $document;

    }


    public function download(string $sessionID, string $documentID):Collection
    {

        $subfolder = config('services.mo.subfolder') ?? "5/2025/";

        $connector = new MonitorulOficialConnector($sessionID);

        for ($page = 1; $page <= 32; $page++) {

            $request = new GetPage($documentID, $subfolder, $page);

            $response = $connector->send($request);


            $content = $response->dtoOrFail();


            if($content == "Error:Incorrect file specified, please check your path")
            {
                return collect([]);
            }

            if ($response->successful() && !empty($response->body()))
            {

                $filename = "monitorul-oficial/{$documentID}/{$page}.pdf";

                Storage::disk('public')->put($filename, $response->body());

                $pdfFiles[] = Storage::disk('public')->path($filename);
            }
        }

        return collect($pdfFiles);
    }


}
