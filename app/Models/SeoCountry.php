<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SeoCountry extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'seo_countries';
    protected $fillable =[
    'name',
    'status',
    'seo_city_title',
    'seo_city_description',
    'seo_city_key_phrase','title','content',];

    public function seoCities(){
        return  $this->hasMany(SeoCity::class,'country_id','id' );
     }
}
