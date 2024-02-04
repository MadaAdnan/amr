<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class NavPages
 *
 * This model represents navigation pages within the application. Navigation pages have
 * attributes such as title and is_orphan. Additionally, it includes a one-to-many
 * relationship with the Page model.
 *
 * Why this model exists:
 * - To store and manage data related to navigation pages in the application.
 * - Provides a structured way to interact with navigation page-related data in the database.
 * - This the navigation pages wil show in the web pages section dashboard
 *
 * @package App\Models
 */

class NavPages extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'is_orphan'
    ];
    
    public function pages()
    {
        return $this->hasMany(Page::class,'nav_page_id');
    }
    
}
