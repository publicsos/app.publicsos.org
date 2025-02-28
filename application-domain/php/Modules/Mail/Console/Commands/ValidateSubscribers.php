<?php
declare(strict_types=1);
namespace Modules\Mail\Console\Commands;

use Illuminate\Console\Command;
use Modules\Mail\Services\Validation\Contracts\ValidationContract;


class ValidateSubscribers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:validate-subscribers';

    protected $description = "Validates subscribers email address using the local validator.";


    private ValidationContract $service;

    public function __construct(ValidationContract $service)
    {
        parent::__construct();

        $this->service = $service;
    }


    /**
     * Validates the subscibers not scanned
     *
      * @return void
     */
    public function handle():void
    {
        $this->info('Checking for existing subscriber...');

        //todo check because the is a json and we need to add validated to

        $subscribers = (new \Modules\Mail\Models\Subscriber)->where('meta', NULL)->get();

        if($subscribers->isEmpty()){
            $this->info("No subscribers found exiting gracefully");
            return;
        }

        foreach ($subscribers as $subscriber) {

            $this->info('Dispatching CheckEmailJob for subscriber...' . $subscriber->email);

            $this->service->isValidEmail($subscriber->email);

        }

    }
}
