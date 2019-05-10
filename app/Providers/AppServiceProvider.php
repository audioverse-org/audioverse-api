<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
       // Set custom serializer to remove 'data' key in API output when desired
      app('Dingo\Api\Transformer\Factory')->setAdapter(function () {
         $fractalManager = new \League\Fractal\Manager;
         $fractalManager->setSerializer(new \App\Foundations\Fractal\NoDataArraySerializer);
         return new \Dingo\Api\Transformer\Adapter\Fractal($fractalManager);
      });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
