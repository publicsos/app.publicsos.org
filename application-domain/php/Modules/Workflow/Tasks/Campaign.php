<?php
declare(strict_types=1);
namespace Modules\Workflow\Tasks;



class Campaign extends Task
{
    public static array $fields = [
        'campaign' => 'campaign',
    ];

    public static array $output = [
        'campaign' => 'campaign_output',
    ];

    public static $icon = '<i class="far fa-eye"></i>';

    public function execute(): void
    {

        $campaign = $this->getData('campaign');

        $this->setData('response', 'Message to be extracted from database');
    }

    public function getSettings()
    {
        return view('workflows::layouts.campaign_overlay', [
            'element' => $this,
        ]);
    }
}
