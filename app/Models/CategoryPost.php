<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
    use HasFactory;

    #pivot table
    protected $table = 'category_post';
    //20231005
    //we make these properies to let laravel know about the table category_post.
    protected $fillable = ['category_id', 'post_id'];
    //we need this for create and save.
    public $timestamps = false;
    //to tell laravel we dont have $timestamps.

    #to get the name of the category
    public function category()
    {
        return $this->belongsTo(Category::class);    
    }
}
