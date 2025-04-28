@extends('layouts.app')
@section('title', 'Categories')
@section('content')
  <section class="py-5">
    <div class="container">
      <h2 class="fs-2 fw-bold mb-4 text-center">Categories</h2>
      @if (isset($categories) && !$categories->isEmpty())
        <div class="row g-4">
          @foreach ($categories as $category)
            <div class="col-md-4">
              <div class="card border-0 shadow-sm rounded-3 overflow-hidden h-100">
                <img src="{{ asset('storage/' . $category->image) }}" class="card-img-top" alt="{{ $category->name }}"
                  style="height: 200px; object-fit: cover;">
                <div class="card-body p-4">
                  <h3 class="card-title fs-5 fw-bold mb-2">{{ Str::limit($category->name, 50) }}</h3>
                  <div class="mb-3">
                    @foreach ($category->posts as $post)
                      <span
                        class="badge bg-primary-subtle text-primary-emphasis rounded-pill me-1">{{ $post->title }}</span>
                    @endforeach
                  </div>
                  <p class="card-text text-muted mb-3">{{ Str::limit(strip_tags($category->desc), 100) }}</p>
                  <a href="{{ route('posts.index') }}?category={{ $category->slug }}"
                    class="btn btn-outline-primary fw-medium">View Posts</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('admin.category.index') }}" class="btn btn-outline-primary fw-medium">See All Categories</a>
        </div>
      @else
        <div class="alert alert-info text-center rounded-3" role="alert">
          No categories available.
        </div>
      @endif
    </div>
  </section>
  <section class="bg-light py-5">
    <div class="container">
      <h2 class="fs-2 fw-bold mb-4 text-center">Explore by Category</h2>
      @if (isset($categories) && !$categories->isEmpty())
        <div class="row g-4">
          @foreach ($categories->take(4) as $category)
            <div class="col-md-3">
              <div class="card border-0 shadow-sm rounded-3 text-center h-100">
                <div class="card-body p-4">
                  <h3 class="fs-5 fw-bold mb-3">{{ $category->name }}</h3>
                  <p class="text-muted mb-3">{{ $category->posts->count() }}
                    Post{{ $category->posts->count() === 1 ? '' : 's' }}</p>
                  <a href="{{ route('posts.index') }}?category={{ $category->slug }}"
                    class="btn btn-primary fw-medium">View Posts</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
        <div class="text-center mt-4">
          <a href="{{ route('admin.category.index') }}" class="btn btn-outline-primary fw-medium">See All Categories</a>
        </div>
      @else
        <div class="alert alert-info text-center rounded-3" role="alert">
          No categories available.
        </div>
      @endif
    </div>
  </section>
@endsection
