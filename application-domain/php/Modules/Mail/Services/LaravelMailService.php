<?php

declare(strict_types=1);

namespace Modules\Mail\Services;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;


class LaravelMailService
{
     /** @var Application */
     private $app;

     public function __construct(Application $app)
     {
         $this->app = $app;
     }



     /**
      * @throws BindingResolutionException
      */
     public function setCurrentWorkspaceIdResolver(callable $resolver): void
     {
         $this->app->make('laravel-mail.resolver')->setCurrentWorkspaceIdResolver($resolver);
     }

     /**
      * @throws BindingResolutionException
      */
     public function currentWorkspaceId(): ?int
     {
         return $this->app->make('laravel-mail.resolver')->resolveCurrentWorkspaceId();
     }

     /**
      * @throws BindingResolutionException
      */
     public function setSidebarHtmlContentResolver(callable $resolver): void
     {
         $this->app->make('laravel-mail.resolver')->setSidebarHtmlContentResolver($resolver);
     }

     /**
      * @throws BindingResolutionException
      */
     public function sidebarHtmlContent(): ?string
     {
         return $this->app->make('laravel-mail.resolver')->resolveSidebarHtmlContent();
     }

     /**
      * @throws BindingResolutionException
      */
     public function setHeaderHtmlContentResolver(callable $resolver): void
     {
         $this->app->make('laravel-mail.resolver')->setHeaderHtmlContentResolver($resolver);
     }

     /**
      * @throws BindingResolutionException
      */
     public function headerHtmlContent(): ?string
     {
         return $this->app->make('laravel-mail.resolver')->resolveHeaderHtmlContent();
     }
}
