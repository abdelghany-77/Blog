<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
  /**
   * Display the home page with latest posts.
   */
  public function home()
  {
    $posts = Post::with('categories')->latest()->take(3)->get();
    $categories = Category::all();

    return view('home', compact('posts', 'categories'));
  }

  /**
   * Display a paginated list of posts.
   */
  public function index(Request $request,Post $post)
  {
    $posts = Post::with('categories')->paginate(4);

    // Check for category filter by slug or id
    $category = null;
    if ($request->has('category')) {
      $categoryIdentifier = $request->category;
      $category = Category::where('slug', $categoryIdentifier)
        ->orWhere('id', $categoryIdentifier)
        ->first();

      if ($category) {
        $posts = $category->posts()->with('categories')->paginate(4);
      }
    }



    return view('posts.index', compact('posts', 'category'));
  }

  /**
   * Display the specified post.
   */
  public function show($id)
  {
    $post = Post::with('categories')->findOrFail($id);

    $postData = $this->preparePostCategoriesData($post);

    // Get related posts
    $categoryIds = $post->categories->pluck('id');
    $relatedPosts = Post::with('categories')
      ->whereHas('categories', function ($query) use ($categoryIds) {
        $query->whereIn('categories.id', $categoryIds);
      })
      ->where('id', '!=', $post->id)
      ->latest()
      ->take(3)
      ->get();

    // Extract YouTube video ID if the post has a YouTube video
    $youtubeVideoId = null;
    if ($post->video && Str::contains($post->video, ['youtube.com', 'youtu.be'])) {
      $youtubePattern = '~(?:https?://)?(?:www\.)?(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})~i';
      preg_match($youtubePattern, $post->video, $matches);
      $youtubeVideoId = $matches[1] ?? null;
    }

    $relatedPostsData = $this->prepareRelatedPostsData($relatedPosts);

    return view('posts.show', array_merge(
      [
        'post' => $post,
        'relatedPosts' => $relatedPosts,
        'relatedPostsData' => $relatedPostsData,
        'youtubeVideoId' => $youtubeVideoId
      ],
      $postData
    ));
  }

  /**
   * Search for posts by title.
   */
  public function search(Request $request)
  {
    $search = $request->input('search');

    $posts = Post::where('title', 'LIKE', "%{$search}%")
      ->with('categories')
      ->paginate(3);

    return view('posts.index', compact('posts', 'search'));
  }

  /**
   * Show the form for creating a new post.
   */
  public function create()
  {
    $mainCategories = Category::whereNull('parent_id')->with('children')->get();
    return view('admin.posts.create', compact('mainCategories'));
  }

  /**
   * Store a newly created post.
   */
  public function store(Request $request)
  {
    $validatedData = $this->validatePost($request);
    $this->processMedia($request, $validatedData);

    $post = Post::create($validatedData);
    $this->syncCategories($post, $request->category_ids);

    return redirect()->route('posts.index')->with('success', 'Post created successfully.');
  }

  /**
   * Show the form for editing the specified post.
   */
  public function edit(Post $post)
  {
    $mainCategories = Category::whereNull('parent_id')->with('children')->get();
    return view('admin.posts.edit', compact('post', 'mainCategories'));
  }

  /**
   * Update the specified post.
   */
  public function update(Request $request, Post $post)
  {
    $validatedData = $this->validatePost($request);
    $this->processMedia($request, $validatedData, $post);

    $post->update($validatedData);
    $this->syncCategories($post, $request->category_ids);

    return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
  }

  /**
   * Remove the specified post.
   */
  public function destroy($id)
  {
    try {
      $post = Post::findOrFail($id);
      // Detach all categories
      $post->categories()->detach();
      // Delete media files
      $this->deletePostMedia($post);
      $post->delete();
      return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    } catch (\Exception $e) {
      return redirect()->route('posts.index')->with('error', 'Error deleting post: ' . $e->getMessage());
    }
  }

  /**
   * Display posts by category.
   */
  public function postsByCategory($id)
  {
    $category = Category::where('id', $id)
      ->orWhere('slug', $id)
      ->firstOrFail();

    $posts = $category->posts()
      ->with('categories')
      ->paginate(5);

    return view('posts.index', compact('posts', 'category'));
  }

  /**
   * Validate post data.
   */
  private function validatePost(Request $request)
  {
    return $request->validate([
      'title' => 'required|string|max:255',
      'desc' => 'nullable|string',
      'image' => 'nullable|image|max:2048',
      'video' => 'nullable|file|mimes:mp4,mov,avi,webm',
      'youtube_video' => 'nullable|string|url',
      'audio' => 'nullable|mimes:mp3,wav',
      'category_ids' => 'required',
      'category_ids.*' => 'exists:categories,id',
    ]);
  }

  /**
   * Process and store media files.
   */
  private function processMedia(Request $request, array &$data, ?Post $post = null)
  {
    // Process image
    if ($request->hasFile('image')) {
      if ($post && $post->image) {
        Storage::disk('public')->delete($post->image);
      }

      $data['image'] = $this->storeFile($request->file('image'), 'posts');
    }

    // Process video (file or YouTube URL)
    if ($request->has('youtube_video') && !empty($request->youtube_video)) {
      if ($post && $post->video && !Str::contains($post->video, ['youtube.com', 'youtu.be'])) {
        Storage::disk('public')->delete($post->video);
      }
      $data['video'] = $request->youtube_video;
    } elseif ($request->hasFile('video')) {
      if ($post && $post->video && !Str::contains($post->video, ['youtube.com', 'youtu.be'])) {
        Storage::disk('public')->delete($post->video);
      }
      $data['video'] = $this->storeFile($request->file('video'), 'posts');
    }

    // Process audio
    if ($request->hasFile('audio')) {
      if ($post && $post->audio) {
        Storage::disk('public')->delete($post->audio);
      }
      $data['audio'] = $this->storeFile($request->file('audio'), 'posts');
    }

    // Remove youtube_video field as it's already processed
    if (isset($data['youtube_video'])) {
      unset($data['youtube_video']);
    }
  }

  /**
   * Store a file and return its path.
   */
  private function storeFile($file, $directory)
  {
    $fileName = 'post_' . uniqid() . '.' . $file->getClientOriginalExtension();
    $path = $directory . '/' . $fileName;
    $file->storeAs($directory, $fileName, 'public');

    return $path;
  }

  /**
   * Delete all media associated with a post.
   */
  private function deletePostMedia(Post $post)
  {
    $mediaFields = ['image', 'audio'];

    foreach ($mediaFields as $field) {
      if ($post->$field) {
        Storage::disk('public')->delete($post->$field);
      }
    }

    // Handle video separately (could be YouTube URL or file)
    if ($post->video && !Str::contains($post->video, ['youtube.com', 'youtu.be'])) {
      Storage::disk('public')->delete($post->video);
    }
  }

  /**
   * Sync categories for a post, including parent categories.
   */
  private function syncCategories(Post $post, array $categoryIds)
  {
    $selectedCategories = Category::whereIn('id', $categoryIds)->get();
    $parentIds = $selectedCategories->whereNotNull('parent_id')->pluck('parent_id')->unique();
    $allCategoryIds = array_unique(array_merge($categoryIds, $parentIds->toArray()));

    $post->categories()->sync($allCategoryIds);
  }

  /**
   * Prepare post categories data for view.
   */
  private function preparePostCategoriesData(Post $post)
  {
    $postCategories = $post->categories;

    // Get parent and child categories
    $parentCategories = $postCategories->filter(fn($category) => is_null($category->parent_id));
    $childCategories = $postCategories->filter(fn($category) => !is_null($category->parent_id));

    // Find child categories (whose parents are not in post categories)
    $orphanedChildCategories = $childCategories->whereNotIn('parent_id', $parentCategories->pluck('id'));

    // Group child categories by parent_id
    $childrenByParent = [];
    foreach ($childCategories as $child) {
      if (!isset($childrenByParent[$child->parent_id])) {
        $childrenByParent[$child->parent_id] = [];
      }
      $childrenByParent[$child->parent_id][] = $child;
    }

    // Find main category for breadcrumb
    $mainCategory = $parentCategories->first() ?: $postCategories->first();

    return [
      'postCategories' => $postCategories,
      'parentCategories' => $parentCategories,
      'childCategories' => $childCategories,
      'orphanedChildCategories' => $orphanedChildCategories,
      'childrenByParent' => $childrenByParent,
      'mainCategory' => $mainCategory
    ];
  }

  /**
   * Prepare related posts data for view.
   */
  private function prepareRelatedPostsData($relatedPosts)
  {
    $relatedPostsData = [];

    foreach ($relatedPosts as $relatedPost) {
      // Get parent category
      $relatedParentCategory = $relatedPost->categories->first(function ($category) {
        return is_null($category->parent_id);
      });

      // If no parent found, use any category
      if (!$relatedParentCategory && $relatedPost->categories->isNotEmpty()) {
        $relatedParentCategory = $relatedPost->categories->first();
      }

      // Get child category if exists
      $relatedChildCategory = null;
      if ($relatedParentCategory) {
        $relatedChildCategory = $relatedPost->categories->first(function ($category) use ($relatedParentCategory) {
          return $category->parent_id == $relatedParentCategory->id;
        });
      }

      // Add excerpt with proper formatting
      $excerpt = Str::limit(strip_tags($relatedPost->desc), 80);

      $relatedPostsData[] = [
        'post' => $relatedPost,
        'parentCategory' => $relatedParentCategory,
        'childCategory' => $relatedChildCategory,
        'excerpt' => $excerpt
      ];
    }

    return $relatedPostsData;
  }

}
