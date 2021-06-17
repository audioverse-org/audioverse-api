<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Agreement extends Model {
   
   protected $table = 'catalogAgreements';
   protected $primaryKey = 'agreementId';

   const CREATED_AT = 'created';
   const UPDATED_AT = 'modified';
   
   /**
   * The event map for the model.
   *
   * @var array
   */
   protected $dispatchesEvents = [
      'updated' => \App\Events\UpdateHiddenFields::class
   ];

   public function owner() {
      return $this->hasOne('App\Owner', 'ownerId', 'ownerId')->where(['active' => 1]);
   }

   public function license() {
      return $this->hasOne('App\License', 'licenseId', 'licenseId')->where(['active' => 1]);
   }

   public function recordings() {
      return $this->hasMany('App\Recording', 'agreementId', 'agreementId')->where([
         'active' => 1
      ]);
   }
}
