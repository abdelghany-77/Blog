<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
  public function run()
  {
    // Create main categories
    $grammar = Category::create([
      'name' => 'Grammar',
      'slug' => 'grammar',
      'description' => 'Grammar lessons and exercises'
    ]);

    $listening = Category::create([
      'name' => 'Listening',
      'slug' => 'listening',
      'description' => 'Listening exercises and tests'
    ]);

    $reading = Category::create([
      'name' => 'Reading',
      'slug' => 'reading',
      'description' => 'Reading materials and comprehension exercises'
    ]);

    // Create Grammar subcategories
    Category::create([
      'name' => 'Tenses',
      'slug' => 'tenses',
      'description' => 'Past, present, future and perfect tenses',
      'parent_id' => $grammar->id
    ]);

    Category::create([
      'name' => 'General',
      'slug' => 'general-grammar',
      'description' => 'General grammar topics and rules',
      'parent_id' => $grammar->id
    ]);

    // Create CEFR levels for Listening
    $levels = ['A1', 'A2', 'B1', 'B2', 'C1'];

    foreach ($levels as $level) {
      Category::create([
        'name' => $level,
        'slug' => 'listening-' . strtolower($level),
        'description' => $level . ' level listening exercises',
        'parent_id' => $listening->id
      ]);
    }

    // Create CEFR levels for Reading
    foreach ($levels as $level) {
      Category::create([
        'name' => $level,
        'slug' => 'reading-' . strtolower($level),
        'description' => $level . ' level reading materials',
        'parent_id' => $reading->id
      ]);
    }
  }
}
