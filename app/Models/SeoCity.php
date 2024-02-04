<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoCity extends Model {

   use HasFactory, SoftDeletes;
   
   protected $table = 'seo_cities';

   protected $fillable = [
      'status',
      'name',
      'slug',
      'country_id',
      'seo_title',
      'seo_description',
      'seo_key_phrase',
      'services_content',
      'services_header'
   ];

   const STATUS_ACTIVE = "Active";
   const STATUS_DISABLED = "Disabled";
   
   public function seoCountry() {
      return $this->belongsTo(SeoCountry::class, 'country_id');
   }
   public function services() {
      return $this->hasMany(Services::class, 'city_id', 'id');
   }

}
