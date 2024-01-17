<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap services.
   */
  public function boot(): void
  {
    $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
    $verticalMenuData = json_decode($verticalMenuJson);

            // Aplicar lógica de filtrado si el usuario tiene el permiso 'view own'
            if (Auth::check() && Auth::user()->can('view own')) {
              $verticalMenuData = array_filter($verticalMenuData['menu'], function ($menuItem) {
                  return in_array($menuItem['name'], ['Dashboard', 'Envios']);
              });
          }


    $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
    $horizontalMenuData = json_decode($horizontalMenuJson);

    // Share all menuData to all the views
    \View::share('menuData', [$verticalMenuData, $horizontalMenuData]);
  }
}
