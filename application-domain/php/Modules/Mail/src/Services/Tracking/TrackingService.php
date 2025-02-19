<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Services\Tracking;
use AlexWestergaard\PhpGa4\Analytics;
use AlexWestergaard\PhpGa4\UserProperty;

use AlexWestergaard\PhpGa4\Event\SelectContent;

/// check for more event folde of the package
/**
 * @todo Implement TrackingService using the Tracking Pixel
 * Class TrackingService
 */

class TrackingService
{
    public Analytics $analytics;

    public function __construct()
    {

        $this->analytics = Analytics::new(
            measurement_id: 'G-V4RXY7MZHW',
            api_secret: 's-OfkKBtRPO2mLzyvFoPQA',

        );

        $client_id = "client_1234";

        $user_id = "user_1234";
        // Set a sample Client ID and User ID
        $this->analytics->setClientId($client_id);
        $this->analytics->setUserId($user_id);
        $this->analytics->addUserProperty($userProperty = UserProperty::fromArray(['name' => 'leadscaptain', 'value' => 'testvalue']));

    }

    /**
     * Send a single event to Google Analytics.
     */
    public function sendEvent(SelectContent $event): mixed
    {
        //prepend some some default that will be later used values
        $event->setItemId("1234");
        $event->setLanguage("en_GB");
        $event->setPageTitle("The page title");
        $event->setPageReferrer("The page referre");

        //$event->setPageLocation("/some-page-location");
        $event->setScreenResolution("1024x768");

        $event->setContentType("email");



        // Add the event to the analytics instance
        $this->analytics->addEvent($event);

        return $this->analytics->post();

    }

}
