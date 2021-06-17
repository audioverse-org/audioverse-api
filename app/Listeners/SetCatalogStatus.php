<?php

namespace App\Listeners;

use App\Presenter;
use App\Events\UpdateCatalogStatus;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SetCatalogStatus
{
  /**
    * Create the event listener.
   *
   * @return void
   */
   public function __construct()
   {
      // Do nothing
   }

   /**
    * Handle the event.
      *
      * @param  UpdateCatalogStatus  $event
      * @return void
      */
   public function handle(UpdateCatalogStatus $event)
   {
      // TODO
   }
}
