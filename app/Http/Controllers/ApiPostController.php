<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class ApiPostController extends Controller
{
  public function index()
  {
    // Fetch posts from the database
    $posts = Post::select('id','title','desc')->get();

    // Return the posts as a JSON response
    return response()->json($posts);
  }

  public function show($id)
  {
    // Fetch a single post by its ID
    $post = Post::findOrFail($id);

    // Return the post as a JSON response
    return response()->json($post);
  }

  public function store(Request $request)
  {
    // Validate the incoming request data
    $validatedData = $request->validate([
      'title' => 'required|string|max:255',
      'desc' => 'required|string',
      'image' => 'required|image|max:2048',
      'category_ids' => 'required|array',
      'category_ids.*' => 'exists:categories,id',
    ]);

    // Store the image path with name of photo with the id
    if ($request->hasFile('image')) {
      $newImageName = 'post_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
      $newImagePath = 'posts/' . $newImageName;
      $request->file('image')->storeAs('posts', $newImageName, 'public');
    }

    // Create a new post in the database
    $post = Post::create([
      'title' => $validatedData['title'],
      'desc' => $validatedData['desc'],
      'image' => $newImagePath,
    ]);

    // Attach categories to the post
    $post->categories()->sync($validatedData['category_ids']);

    // Return a success response
    return response()->json(['message' => 'Post created successfully'], 201);
  }
  public function update(Request $request, $id)
  {
    // Validate the incoming request data
    $validatedData = $request->validate([
      'title' => 'nullable|string|max:255',
      'desc' => 'nullable|string',
      'image' => 'nullable|image|max:2048',
      'category_ids' => 'nullable|array',
      'category_ids.*' => 'exists:categories,id',
    ]);

    // Fetch the post by its ID
    $post = Post::findOrFail($id);

    // Update the post's attributes
    if (isset($validatedData['title'])) {
      $post->title = $validatedData['title'];
    }
    if (isset($validatedData['desc'])) {
      $post->desc = $validatedData['desc'];
    }
    if ($request->hasFile('image')) {
      $newImageName = 'post_' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
      $newImagePath = 'posts/' . $newImageName;
      $request->file('image')->storeAs('posts', $newImageName, 'public');
      $post->image = $newImagePath;
    }

    // Save the updated post
    $post->save();

    // Update categories if provided
    if (isset($validatedData['category_ids'])) {
      $post->categories()->sync($validatedData['category_ids']);
    }

    // Return a success response
    return response()->json(['message' => 'Post updated successfully']);
  }
  public function destroy($id)
  {
    // Fetch the post by its ID
    $post = Post::findOrFail($id);

    // Delete the old image if it exists
    if ($post->image) {
      $oldImagePath = public_path('storage/' . $post->image);
      if (file_exists($oldImagePath)) {
        unlink($oldImagePath);
      }
    }

    // Delete the post from the database
    $post->delete();
    // Return a success response
    return response()->json(['message' => 'Post deleted successfully']);
  }
  public function search(Request $request)
  {
    // Validate the search query
    $validatedData = $request->validate([
      'query' => 'required|string|max:255',
    ]);
    // Fetch posts matching the search query
    $posts = Post::where('title', 'LIKE', '%' . $validatedData['query'] . '%')->get();
    // Return the posts as a JSON response
    return response()->json($posts);
  }
}
