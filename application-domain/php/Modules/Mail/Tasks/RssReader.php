<?php
declare(strict_types=1);
namespace Modules\Mail\Tasks;

use Illuminate\Support\Facades\Http;
use willvincent\Feeds\Facades\FeedsFacade;

class RssReader extends Task
{
    public static array $fields = [
        'url' => 'url',
    ];

    public static array $output = [
        'output' => 'output',
    ];

    public static $icon = '<i class="fas fa-rss"></i>';

    public function execute(): void
    {

        info("The url is ".$this->getData('url'));
        $feed = FeedsFacade::make($this->getData('url'));

        $data = array(
            'title'     => $feed->get_title(),
            'permalink' => $feed->get_permalink(),
            'items'     => $feed->get_items(),
        );

        try {

            $content = Http::get($this->getData('url'))->body();


            info("The fields are ". json_encode($feed->get_items()));

            $this->setData('output', $data);

            info("The feed is ready and the data is -> " . json_encode($content)   );

        } catch (\Exception $e) {

            info($e->getMessage());
        }
    }
}
