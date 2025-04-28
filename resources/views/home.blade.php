@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
  <!-- Hero Section -->
  <section class="hero-section bg-primary bg-gradient text-white py-5 mb-4 rounded-4">
    <div class="container py-4 py-lg-5">
      <div class="row align-items-center">
        <div class="col-lg-7 mb-4 mb-lg-0">
          <h1 class="display-4 fw-bold mb-3">Welcome to Our Blog</h1>
          <p class="lead fs-4 mb-4 opacity-90">Discover insightful articles, tips, and stories from our vibrant community.
            Dive into a
            world of knowledge and inspiration!</p>
          <div class="d-flex gap-3">
            <a href="{{ route('posts.index') }}" class="btn btn-light btn-lg fw-medium">
              <i class="bi bi-journal-text me-2"></i>Explore Posts
            </a>
            @if (!Auth::check())
              <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg fw-medium">
                <i class="bi bi-person-circle me-2"></i>Sign In
              </a>
            @endif
          </div>
          <div class="mt-4 d-flex gap-2">
            <span class="badge bg-light text-primary fs-6 py-2 px-3">
              <i class="bi bi-calendar-date me-2"></i>{{ now()->format('Y-m-d') }}
            </span>
            @if (Auth::check())
              <span class="badge bg-light text-primary fs-6 py-2 px-3">
                <i class="bi bi-person-check me-2"></i>Welcome, {{ Auth::user()->name }}
              </span>
            @endif
          </div>
        </div>
        <div class="col-lg-4 text-center d-none d-lg-block">
          <div class="position-relative">
            <img src="https://www.vuelio.com/uk/wp-content/uploads/2020/09/Education-Top-10-UK-Blogs.jpg" class="img-fluid rounded-4 shadow"
              alt="Blog Hero Image">
            <div class="position-absolute top-0 start-100 translate-middle bg-warning p-3 rounded-circle"
              style="z-index: 1;">
              <span class="fw-bold text-dark">NEW</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Posts Section -->
  <section class="py-5">
    <div class="container">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fs-1 fw-bold mb-0">
          <i class="bi bi-stars me-2 text-warning"></i>Featured Posts
        </h2>
        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary fw-medium">
          <i class="bi bi-grid-3x3-gap me-2"></i>See All Posts
        </a>
      </div>

      @if (isset($posts) && !$posts->isEmpty())
        <div class="row g-4">
          @foreach ($posts->take(3) as $post)
            <div class="col-md-4">
              <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 hover-shadow">
                <div class="img-hover-zoom">
                  @if ($post->image)
                    <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top" alt="{{ $post->title }}"
                      style="height: 200px; object-fit: cover;">
                  @else
                    <div class="bg-light d-flex justify-content-center align-items-center" style="height: 200px;">
                      <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                    </div>
                  @endif
                </div>
                <div class="card-body p-4">
                  <div class="d-flex mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3 rounded-pill">
                      <i class="bi bi-clock me-1"></i>{{ $post->created_at->format('M d, Y') }}
                    </span>
                  </div>
                  <h3 class="card-title fs-5 fw-bold mb-2">
                    <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-dark stretched-link">
                      {{ Str::limit($post->title, 50) }}
                    </a>
                  </h3>
                  <div class="mb-3">
                    @foreach ($post->categories->take(2) as $category)
                      <span
                        class="badge bg-primary-subtle text-primary-emphasis rounded-pill me-1">{{ $category->name }}</span>
                    @endforeach
                    @if ($post->categories->count() > 2)
                      <span
                        class="badge bg-light text-secondary rounded-pill">+{{ $post->categories->count() - 2 }}</span>
                    @endif
                  </div>
                  <p class="card-text text-muted mb-3">{{ Str::limit(strip_tags($post->desc), 100) }}</p>
                  <div class="d-flex justify-content-between align-items-center">
                    <div
                      class="avatar bg-primary-subtle rounded-circle text-primary d-flex align-items-center justify-content-center"
                      style="width: 32px; height: 32px;">
                      <i class="bi bi-person-fill"></i>
                    </div>
                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-primary">
                      Read More <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light">
          <div class="card-body">
            <i class="bi bi-journal-x display-4 text-muted mb-3"></i>
            <h3>No Featured Posts Available</h3>
            <p class="text-muted">We're working on adding exciting content soon!</p>

            @if (Auth::check())
              <a href="{{ route('admin.posts.create') }}" class="btn btn-primary mt-3">
                <i class="bi bi-plus-lg me-1"></i> Create the First Post
              </a>
            @endif
          </div>
        </div>
      @endif
    </div>
  </section>

  <!-- Category Spotlight Section -->
  <section class="py-5 bg-light rounded-4 my-4">
    <div class="container py-3">
      <div class="d-flex align-items-center justify-content-between mb-4">
        <h2 class="fs-1 fw-bold mb-0">
          <i class="bi bi-bookmarks me-2 text-success"></i>Explore by Category
        </h2>
        <a href="{{ route('admin.category.index') }}" class="btn btn-outline-success fw-medium">
          <i class="bi bi-grid-3x3-gap me-2"></i>All Categories
        </a>
      </div>

      @if (isset($categories) && !$categories->isEmpty())
        <div class="row g-4">
          @foreach ($categories->take(4) as $category)
            <div class="col-md-6 col-lg-3">
              <div class="card border-0 shadow-sm rounded-4 text-center h-100 hover-shadow">
                <div class="card-body p-4">
                  <div
                    class="icon-circle bg-success bg-opacity-10 text-success mx-auto mb-3 d-flex align-items-center justify-content-center"
                    style="width: 70px; height: 70px; border-radius: 50%;">
                    <i class="bi bi-tag-fill fs-3"></i>
                  </div>
                  <h3 class="fs-5 fw-bold mb-3">{{ $category->name }}</h3>
                  <p class="text-muted mb-3">
                    <span class="badge bg-success bg-opacity-10 text-success fs-6 py-2 px-3 rounded-pill">
                      <i class="bi bi-journal me-1"></i>
                      {{ $category->posts->count() }} Post{{ $category->posts->count() === 1 ? '' : 's' }}
                    </span>
                  </p>
                  <a href="{{ route('posts.index') }}?category={{ $category->slug }}"
                    class="btn btn-outline-success fw-medium stretched-link">View Category</a>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      @else
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
          <div class="card-body">
            <i class="bi bi-tags display-4 text-muted mb-3"></i>
            <h3>No Categories Available</h3>
            <p class="text-muted">Categories help organize your content better.</p>

            @if (Auth::check())
              <a href="{{ route('admin.category.create') }}" class="btn btn-success mt-3">
                <i class="bi bi-plus-lg me-1"></i> Create a Category
              </a>
            @endif
          </div>
        </div>
      @endif
    </div>
  </section>

  <!-- Recent Posts Section -->
  <section class="py-5">
    <div class="container">
      <h2 class="fs-1 fw-bold mb-4">
        <i class="bi bi-clock-history me-2 text-info"></i>Recent Updates
      </h2>

      @if (isset($posts) && !$posts->isEmpty())
        <div class="row g-4">
          @foreach ($posts->take(4) as $key => $post)
            @if ($key === 0)
              <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden hover-shadow">
                  <div class="row g-0">
                    <div class="col-md-6 img-hover-zoom">
                      @if ($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="img-fluid h-100 object-fit-cover"
                          alt="{{ $post->title }}">
                      @else
                        <div class="bg-light d-flex justify-content-center align-items-center h-100"
                          style="min-height: 300px;">
                          <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                        </div>
                      @endif
                    </div>
                    <div class="col-md-6">
                      <div class="card-body p-4 p-md-5">
                        <div class="d-flex mb-3">
                          <span class="badge bg-info bg-opacity-10 text-info py-2 px-3 rounded-pill">
                            <i class="bi bi-star-fill me-1"></i>Latest Post
                          </span>
                        </div>
                        <h3 class="fs-3 fw-bold mb-3">{{ $post->title }}</h3>
                        <div class="mb-3">
                          @foreach ($post->categories as $category)
                            <span
                              class="badge bg-primary-subtle text-primary-emphasis rounded-pill me-1">{{ $category->name }}</span>
                          @endforeach
                        </div>
                        <p class="card-text fs-5 text-muted mb-4">{{ Str::limit(strip_tags($post->desc), 150) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                          <span class="text-muted small">
                            <i class="bi bi-calendar3 me-1"></i>
                            {{ $post->created_at->format('M d, Y') }}
                          </span>
                          <a href="{{ route('posts.show', $post) }}" class="btn btn-primary">
                            Read Article <i class="bi bi-arrow-right ms-1"></i>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          @endforeach
        </div>
      @endif
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="py-5 bg-dark text-white rounded-4 my-4">
    <div class="container text-center py-4">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <h2 class="display-6 fw-bold mb-3">Join Our Community</h2>
          <p class="lead fs-4 text-white-50 mb-4">Stay updated with the latest posts and share your own stories!</p>
          <div class="d-flex justify-content-center gap-3">
            @if (Auth::check())
              <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-lg fw-medium">
                <i class="bi bi-pencil-square me-2"></i>Start Writing
              </a>
              <a href="{{ route('posts.index') }}" class="btn btn-outline-light btn-lg fw-medium">
                <i class="bi bi-search me-2"></i>Browse Content
              </a>
            @else
              <a href="{{ route('login') }}" class="btn btn-primary btn-lg fw-medium">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
              </a>
              {{-- <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg fw-medium">
                <i class="bi bi-person-plus me-2"></i>Sign Up Now
              </a> --}}
            @endif
          </div>
          <div class="mt-5">
            <div class="row row-cols-2 row-cols-md-4 g-3 justify-content-center">
              <div class="col">
                <div class="stat-box bg-dark bg-opacity-50 rounded-4 p-3 text-center border border-secondary">
                  <div class="fs-2 fw-bold text-primary">{{ $posts->count() ?? 0 }}</div>
                  <div class="text-white-50">Blog Posts</div>
                </div>
              </div>
              <div class="col">
                <div class="stat-box bg-dark bg-opacity-50 rounded-4 p-3 text-center border border-secondary">
                  <div class="fs-2 fw-bold text-primary">{{ $categories->count() ?? 0 }}</div>
                  <div class="text-white-50">Categories</div>
                </div>
              </div>
              <div class="col">
                <div class="stat-box bg-dark bg-opacity-50 rounded-4 p-3 text-center border border-secondary">
                  <div class="fs-2 fw-bold text-primary">
                    <i class="bi bi-calendar"></i>
                  </div>
                  <div class="text-white-50">{{ now()->format('Y') }}</div>
                </div>
              </div>
              <div class="col">
                <div class="stat-box bg-dark bg-opacity-50 rounded-4 p-3 text-center border border-secondary">
                  <div class="fs-2 fw-bold text-primary">
                    <i class="bi bi-people"></i>
                  </div>
                  <div class="text-white-50">Community</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('styles')
  <style>
    .hero-section {
      position: relative;
      overflow: hidden;
    }

    .hero-section::before {
      content: "";
      position: absolute;
      top: 0;
      right: 0;
      width: 300px;
      height: 300px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      transform: translate(50%, -50%);
    }

    .opacity-90 {
      opacity: 0.9;
    }

    .hover-shadow {
      transition: all 0.3s ease;
    }

    .hover-shadow:hover {
      transform: translateY(-5px);
      box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

    .stretched-link::after {
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      z-index: 1;
      content: "";
    }

    .img-hover-zoom {
      overflow: hidden;
    }

    .img-hover-zoom img {
      transition: transform 0.5s ease;
    }

    .img-hover-zoom:hover img {
      transform: scale(1.05);
    }

    .icon-circle {
      transition: all 0.3s ease;
    }

    .card:hover .icon-circle {
      transform: scale(1.1);
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const statBoxes = document.querySelectorAll('.stat-box');
      statBoxes.forEach(box => {
        box.addEventListener('mouseover', function() {
          this.classList.add('bg-primary');
          this.classList.remove('bg-dark');
        });
        box.addEventListener('mouseout', function() {
          this.classList.remove('bg-primary');
          this.classList.add('bg-dark');
        });
      });
      const hoverShadow = document.querySelectorAll('.hover-shadow');
      hoverShadow.forEach(card => {
        card.addEventListener('mouseover', function() {
          this.classList.add('shadow-lg');
        });
        card.addEventListener('mouseout', function() {
          this.classList.remove('shadow-lg');
        });
      });
      const imgHoverZoom = document.querySelectorAll('.img-hover-zoom');
      imgHoverZoom.forEach(img => {
        img.addEventListener('mouseover', function() {
          this.querySelector('img').style.transform = 'scale(1.05)';
        });
        img.addEventListener('mouseout', function() {
          this.querySelector('img').style.transform = 'scale(1)';
        });
      });
      const iconCircle = document.querySelectorAll('.icon-circle');
      iconCircle.forEach(icon => {
        icon.addEventListener('mouseover', function() {
          this.classList.add('bg-primary');
          this.classList.remove('bg-success');
        });
        icon.addEventListener('mouseout', function() {
          this.classList.remove('bg-primary');
          this.classList.add('bg-success');
        });
      });
      const stretchedLink = document.querySelectorAll('.stretched-link');
      stretchedLink.forEach(link => {
        link.addEventListener('mouseover', function() {
          this.classList.add('text-decoration-none');
        });
        link.addEventListener('mouseout', function() {
          this.classList.remove('text-decoration-none');
        });
      });
      const cardBody = document.querySelectorAll('.card-body');
      cardBody.forEach(body => {
        body.addEventListener('mouseover', function() {
          this.classList.add('bg-light');
        });
        body.addEventListener('mouseout', function() {
          this.classList.remove('bg-light');
        });
      });
    });
  </script>
@endpush
