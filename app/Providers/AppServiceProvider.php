<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register()
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot()
  {
      View::composer('*', function ($view) {
          if (Auth::check()) {
              $view->with('unreadNotifications', Auth::user()->unreadNotifications);
              $view->with('readNotifications', Auth::user()->readNotifications);
          }
      });
  }
}
