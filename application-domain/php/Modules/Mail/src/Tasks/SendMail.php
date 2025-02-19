<?php

namespace LaravelCompany\Mail\Tasks;

class SendMail extends Task
{
    public static array $fields = [
        'Subject' => 'subject',
        'Recipients' => 'recipients',
        'Sender' => 'sender',
        'Content' => 'content',
        'Files' => 'files',
        'File_Name' => 'file_name',
        'CC' => 'cc',
        'BCC' => 'bcc',
    ];

    public static $icon = '<i class="far fa-envelope"></i>';

    public function execute(): void
    {
        try {
            info('Executing Task '.$this->name);

            $dataBus = $this->dataBus;


            info('Sending Mail' . json_encode($dataBus->toString()));

            \Mail::html($dataBus->get('content'), function ($message) use ($dataBus) {
                $message->subject($dataBus->get('subject'))
                    ->to($dataBus->get('recipients'))
                    ->from($dataBus->get('sender'));

                if (is_array($dataBus->get('files'))) {
                    foreach ($dataBus->get('files') as $file) {
                        $message->attachData($file, $dataBus->get('file_name'), [
                            'mime' => 'application/pdf',
                        ]);
                    }
                }
                if (! empty($dataBus->get('cc'))) {
                    $message->cc($dataBus->get('cc'));
                }
                if (! empty($dataBus->get('bcc'))) {
                    $message->bcc($dataBus->get('bcc'));
                }
            });

        }catch (\Exception $e){
            info('Executing Task '.$this->name);
        }
    }
}
