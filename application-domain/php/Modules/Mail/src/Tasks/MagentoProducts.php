<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Tasks;



class MagentoProducts extends Task
{
    public static array $fields = [
        'url' => 'url',

    ];

    public static array $output = [
        'output' => 'output',
    ];

    public static $icon = '<i class="fas fa-cart-arrow-down"></i>';

    public function execute(): void
    {

        info("The magento store url url is ".$this->getData('url'));

        $this->output = "The magento store url url is ".$this->getData('url');

    }
}
