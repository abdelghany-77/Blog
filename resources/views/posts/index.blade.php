@extends('layouts.app')

@section('title', 'Blog Posts')

@section('content')
  <!-- Page Header -->
  <div class="page-header bg-light rounded-4 p-4 mb-4">
    <div class="row align-items-center">
      <div class="col-lg-6">
        <h1 class="fs-2 fw-bold mb-2">
          <i class="bi bi-journal-richtext me-2 text-primary"></i>Blog Posts
        </h1>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item active">Posts</li>

            @if (request()->has('category'))
              @if ($category)
                <li class="breadcrumb-item active">{{ $category->name }}</li>
              @endif
            @endif

            @if (request()->has('search'))
              <li class="breadcrumb-item active">Search: "{{ request()->search }}"</li>
            @endif
          </ol>
        </nav>
      </div>
      <div class="col-lg-6 text-lg-end mt-3 mt-lg-0">
        @if (Auth::check())
          <div class="d-flex gap-3 justify-content-lg-end">
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
              <i class="bi bi-plus-lg me-1"></i> Create Post
            </a>
            <a href="{{ route('admin.category.index') }}" class="btn btn-outline-primary">
              <i class="bi bi-tag me-1"></i> Categories
            </a>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Display posts -->
  @if ($posts->isEmpty())
    <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
      <div class="card-body py-5">
        <i class="bi bi-file-earmark-x display-4 text-muted mb-3"></i>
        <h3>No posts available</h3>

        @if (request()->has('search') || request()->has('category'))
          <p class="text-muted">No posts match your current filter criteria.</p>
          <a href="{{ route('posts.index') }}" class="btn btn-outline-primary mt-3">
            <i class="bi bi-arrow-left me-1"></i> Clear filters and show all posts
          </a>
        @else
          <p class="text-muted">Start adding content to display posts here.</p>
          @if (Auth::check())
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mt-3">
              <i class="bi bi-plus-lg me-1"></i> Create Your First Post
            </a>
          @endif
        @endif
      </div>
    </div>
  @else

    <div class="row g-4">
      @foreach ($posts as $post)
        <div class="col-12">
          <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-card">
            <div class="row g-0">
              <div class="col-md-4 img-hover-zoom position-relative">
                @if ($post->image)
                  <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid h-100 object-fit-cover"
                    alt="{{ $post->title }}" style="min-height: 240px;">
                @else
                  <div class="bg-light h-100 d-flex justify-content-center align-items-center" style="min-height: 240px;">
                    <i class="bi bi-journal-richtext text-secondary" style="font-size: 3rem;"></i>
                  </div>
                @endif

                <!-- Date badge -->
                <div class="position-absolute top-0 start-0 m-3">
                  <div class="bg-white rounded shadow-sm text-center p-2" style="width: 60px; height: 60px;">
                    <span class="d-block text-primary fw-bold">{{ $post->created_at->format('d') }}</span>
                    <small class="text-muted">{{ $post->created_at->format('M') }}</small>
                  </div>
                </div>
              </div>
              <div class="col-md-8">
                <div class="card-body p-4">
                  <!-- Categories -->
                  <div class="mb-3">
                    @php
                      // Get parent categories
                      $parentCategories = $post->categories->filter(function ($category) {
                          return is_null($category->parent_id);
                      });

                      // Get child categories
                      $childCategories = $post->categories->filter(function ($category) {
                          return !is_null($category->parent_id);
                      });
                    @endphp

                    @foreach ($parentCategories as $category)
                      <a href="{{ route('posts.index', ['category' => $category->id]) }}" class="text-decoration-none">
                        <span class="badge bg-primary text-white rounded-pill me-1 mb-1 py-2 px-3">
                          <i class="bi bi-folder me-1"></i>{{ $category->name }}
                        </span>
                      </a>
                    @endforeach

                    @foreach ($childCategories as $category)
                      <a href="{{ route('posts.index', ['category' => $category->id]) }}" class="text-decoration-none">
                        <span
                          class="badge bg-light text-primary border border-primary-subtle rounded-pill me-1 mb-1 py-2 px-3">
                          <i class="bi bi-tag-fill me-1"></i>{{ $category->name }}
                        </span>
                      </a>
                    @endforeach
                  </div>

                  <!-- Title -->
                  <h2 class="card-title fs-4 fw-bold mb-2">
                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark stretched-link">
                      {{ $post->title }}
                    </a>
                  </h2>

                  <!-- Description -->
                  <p class="card-text text-muted mb-3">
                    {{ Str::limit(strip_tags($post->desc), 150) }}
                  </p>

                  <!-- Post meta info -->
                  <div class="d-flex flex-wrap justify-content-end align-items-center mb-3">
                    <div class="d-flex gap-3">
                      <span class="text-muted small d-flex align-items-center">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ $post->created_at->format('M d, Y') }}
                      </span>

                      @if ($post->created_at != $post->updated_at)
                        <span class="text-muted small d-flex align-items-center">
                          <i class="bi bi-pencil-square me-1"></i>
                          {{ $post->updated_at->format('M d, Y') }}
                        </span>
                      @endif
                    </div>
                  </div>

                  <!-- Actions -->
                  <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-primary">
                      Read More <i class="bi bi-arrow-right ms-1"></i>
                    </a>

                    @if (Auth::check())
                      <div class="dropdown ms-2">
                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                          data-bs-toggle="dropdown" aria-expanded="false">
                          <i class="bi bi-gear"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                          <li>
                            <a class="dropdown-item" href="{{ route('admin.posts.edit', $post->id) }}">
                              <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Post
                            </a>
                          </li>
                          <li>
                            <hr class="dropdown-divider">
                          </li>
                          <li>
                            <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST"
                              class="delete-post-form">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-trash me-2"></i> Delete Post
                              </button>
                            </form>
                          </li>
                        </ul>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

    <!-- Pagination links -->
    <div class="d-flex justify-content-center mt-5">
      {{ $posts->withQueryString()->links('pagination.bootstrap-5') }}
    </div>

  @endif
@endsection

@push('styles')
  <style>
    /* Link styling */
    .stretched-link::after {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      z-index: 1;
      content: "";
    }

    .dropdown-toggle::after {
      display: none;
    }

    .dropdown .btn {
      position: relative;
      z-index: 2;
    }

    /* Card styling */
    .hover-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }

    /* Image hover effects */
    .img-hover-zoom {
      overflow: hidden;
    }

    .img-hover-zoom img {
      transition: transform 0.5s ease;
    }

    .hover-card:hover .img-hover-zoom img {
      transform: scale(1.05);
    }

    /* Badge styling */
    .badge {
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .badge:hover {
      transform: translateY(-2px);
    }

    /* Category filter */
    .category-filter {
      overflow-x: auto;
      flex-wrap: nowrap;
      padding-bottom: 5px;
      margin-bottom: -5px;
    }

    .category-filter::-webkit-scrollbar {
      height: 4px;
    }

    .category-filter::-webkit-scrollbar-thumb {
      background-color: rgba(13, 110, 253, 0.2);
      border-radius: 4px;
    }

    .category-filter::-webkit-scrollbar-track {
      background-color: rgba(0, 0, 0, 0.05);
    }

    .category-filter .btn {
      white-space: nowrap;
      border-radius: 30px;
      margin-right: 0.25rem;
      padding-left: 1rem;
      padding-right: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
      .page-header {
        text-align: center;
      }

      .page-header .btn-group {
        justify-content: center;
        margin-top: 1rem;
      }
    }

    /* Pagination styling */
    .page-item.active .page-link {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .page-link {
      color: #0d6efd;
    }

    .page-link:hover {
      color: #0a58ca;
    }

    /* Delete confirmation modal */
    .modal-confirm .modal-content {
      border-radius: 1rem;
      border: none;
    }

    .modal-confirm .modal-header {
      border-bottom: none;
    }

    .modal-confirm .modal-footer {
      border-top: none;
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Handle delete post confirmation with custom modal
      const deleteForms = document.querySelectorAll('.delete-post-form');

      deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
          e.preventDefault();

          const postTitle = this.closest('.card').querySelector('.card-title').textContent.trim();

          if (confirm(`Are you sure you want to delete "${postTitle}"? This action cannot be undone.`)) {
            this.submit();
          }
        });
      });

      // Category Filter Scroll Indicators
      const categoryFilter = document.querySelector('.category-filter');

      if (categoryFilter) {
        // Check if scrolling is possible
        function checkScroll() {
          const isScrollable = categoryFilter.scrollWidth > categoryFilter.clientWidth;

          if (isScrollable) {
            categoryFilter.classList.add('pe-4');

            // Optional: Add visual indicators that there's more to scroll
            // This could be arrows or gradients on the sides
          }
        }

        // Run on page load
        checkScroll();

        // Run on window resize
        window.addEventListener('resize', checkScroll);
      }

      // Update current time
      function updateTime() {
        const timeElements = document.querySelectorAll('.current-time');
        if (timeElements.length > 0) {
          const now = new Date();
          const hours = String(now.getHours()).padStart(2, '0');
          const minutes = String(now.getMinutes()).padStart(2, '0');
          const seconds = String(now.getSeconds()).padStart(2, '0');

          timeElements.forEach(el => {
            el.textContent = `${hours}:${minutes}:${seconds}`;
          });
        }
      }

      // Update time display every second if present
      const hasTimeElements = document.querySelectorAll('.current-time').length > 0;
      if (hasTimeElements) {
        updateTime();
        setInterval(updateTime, 1000);
      }
    });
  </script>
@endpush
