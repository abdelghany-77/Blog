<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
  protected $fillable =[
    'title',
    'desc',
    'image',
    'video',
    'audio',
    'category_id',
  ];

  public function categories()
  {
    return $this->belongsToMany(Category::class);
  }
}
