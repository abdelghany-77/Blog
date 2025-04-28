<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'slug',
    'description',
    'parent_id',
    'order',
    'is_active'
  ];

  // Get parent category
  public function parent()
  {
    return $this->belongsTo(Category::class, 'parent_id');
  }

  // Get child categories
  public function children()
  {
    return $this->hasMany(Category::class, 'parent_id');
  }

  // Get all posts in this category
  public function posts()
  {
    return $this->belongsToMany(Post::class);
  }

  // Check if category has children
  public function hasChildren()
  {
    return $this->children()->count() > 0;
  }

  // Get all categories with their children
  public static function getNestedCategories()
  {
    return self::with('children')
      ->whereNull('parent_id')
      ->orderBy('order')
      ->get();
  }
}
