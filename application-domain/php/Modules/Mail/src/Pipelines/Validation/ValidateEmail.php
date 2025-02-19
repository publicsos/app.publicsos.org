<?php
declare(strict_types=1);
namespace LaravelCompany\Mail\Pipelines\Validation;
use LaravelCompany\Mail\DTO\ImportEmailDTO;
use Log;
use PgSql\Lob;

class ValidateEmail
{

    public function __construct()
    {
       
    }


    /**
     * Validate the email
     
     * @return mixed
     */
    public function handle(ImportEmailDTO $email, $next)
    {
        if($this->isValidEmail($email)) {
            return $next($email);
        }

        Log::error('Invalid email');
    }

    /**
     * 
     * Validate the email
     *
     * @param $email
     * @return bool
     */
    protected function isValidEmail($email): bool
    {
        
        return true;
    }

}
