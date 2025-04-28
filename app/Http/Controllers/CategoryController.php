<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  public function home()
  {
    $categories = Category::with(['children', 'posts'])->whereNull('parent_id')->get();
    return view('category.index ', compact('categories'));
  }
  public function index()
  {
    $categories = Category::with(['children', 'posts'])->whereNull('parent_id')->get();
    return view('admin.category.index ', compact('categories'));
  }

  public function create()
  {
    $categories = Category::whereNull('parent_id')->get();
    // Get all categories that are not parents
    $nonParentCategories = Category::whereNotNull('parent_id')->get();
    // Get all categories that are parents
    $parentCategories = Category::whereNull('parent_id')->get();
    return view('admin.category.create', compact('categories', 'nonParentCategories', 'parentCategories'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'parent_id' => 'nullable|exists:categories,id',
      'is_active' => 'boolean',
    ]);
    Category::create([
      'name' => $request->name,
      'slug' => Str::slug($request->name),
      'description' => $request->description,
      'parent_id' => $request->parent_id,
      'is_active' => $request->has('is_active'),
      'order' => $request->order ?? 0,
    ]);
    return redirect()->route('admin.categories.index')
      ->with('success', 'Category created successfully.');
  }

  public function edit(Category $category)
  {
    $categories = Category::where('id', '!=', $category->id)->get();
    return view('admin.category.edit', compact('category', 'categories'));
  }

  public function update(Request $request, Category $category)
  {
    $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string',
      'parent_id' => 'nullable|exists:categories,id',
      'is_active' => 'boolean',
    ]);
    // Make sure we don't set a category as its own parent
    if ($request->parent_id == $category->id) {
      return back()->withErrors(['parent_id' => 'A category cannot be its own parent.']);
    }
    $category->update([
      'name' => $request->name,
      'slug' => Str::slug($request->name),
      'description' => $request->description,
      'parent_id' => $request->parent_id,
      'is_active' => $request->has('is_active'),
      'order' => $request->order ?? 0,
    ]);
    return redirect()->route('admin.categories.index')
      ->with('success', 'Category updated successfully.');
  }

  public function destroy($id)
  {
    $category = Category::findOrFail($id);
    $category->posts()->detach();
    $category->delete();
    return redirect()->route('admin.category.index')->with('success', 'category deleted successfully.');
  }
}
