@extends('layouts.app')

@section('title', $post->title)

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-10">
      <!-- Breadcrumb with Categories -->
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('posts.index') }}" class="text-decoration-none">Posts</a></li>

          @if ($post->categories->isNotEmpty())
            @php
              // Get parent and child categories
              $parentCategories = $post->categories->whereNull('parent_id');
              $childCategories = $post->categories->whereNotNull('parent_id');

              // Get the first parent category
              $mainCategory = $parentCategories->first() ?: $post->categories->first();
            @endphp

            @if ($mainCategory)
              <li class="breadcrumb-item">
                <a href="{{ route('posts.category', $mainCategory->id) }}" class="text-decoration-none">
                  {{ $mainCategory->name }}
                </a>
              </li>
            @endif
          @endif

          <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 40) }}</li>
        </ol>
      </nav>

      <!-- Post Content -->
      <div class="card border-1 shadow-sm rounded-4 mb-4">
        <div class="card-body p-md-5 p-4">
          <!-- Post Header -->
          <div class="mb-4 d-flex justify-content-between align-items-start">
            <h1 class="card-title fs-2 fw-bold mb-0">{{ $post->title }}</h1>

            @if (Auth::check())
              <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                  aria-expanded="false">
                  <i class="bi bi-three-dots"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-3">
                  <li>
                    <a class="dropdown-item" href="{{ route('admin.posts.edit', $post->id) }}">
                      <i class="bi bi-pencil-square me-2 text-primary"></i> Edit Post
                    </a>
                  </li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li>
                    <form action="{{ route('admin.posts.destroy', $post->id) }}" method="POST">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="dropdown-item text-danger"
                        onclick="return confirm('Are you sure you want to delete this post?')">
                        <i class="bi bi-trash me-2"></i> Delete Post
                      </button>
                    </form>
                  </li>
                </ul>
              </div>
            @endif
          </div>
          <!-- Categories Section -->
          <div class="mb-4 category-section">
            @if ($parentCategories->isNotEmpty())
              @foreach ($parentCategories as $parentCategory)
                <div class="mb-2">
                  <a href="{{ route('posts.category', $parentCategory->id) }}" class="text-decoration-none">
                    <span class="badge bg-primary text-white rounded-pill me-1 py-2 px-3 parent-category">
                      <i class="bi bi-folder me-1"></i>{{ $parentCategory->name }}
                    </span>
                  </a>

                  @if (isset($childrenByParent[$parentCategory->id]))
                    @foreach ($childrenByParent[$parentCategory->id] as $childCategory)
                      <a href="{{ route('posts.category', $childCategory->id) }}" class="text-decoration-none">
                        <span
                          class="badge bg-light text-primary border border-primary-subtle rounded-pill me-1 py-2 px-3">
                          <i class="bi bi-tag-fill me-1"></i>{{ $childCategory->name }}
                        </span>
                      </a>
                    @endforeach
                  @endif
                </div>
              @endforeach
            @endif

            @if ($childCategories->whereNotIn('parent_id', $parentCategories->pluck('id'))->isNotEmpty())
              <div class="mb-2">
                @foreach ($childCategories->whereNotIn('parent_id', $parentCategories->pluck('id')) as $orphanedChild)
                  <a href="{{ route('posts.category', $orphanedChild->id) }}" class="text-decoration-none">
                    <span class="badge bg-secondary-subtle text-secondary-emphasis rounded-pill me-1 py-2 px-3">
                      <i class="bi bi-tag me-1"></i>{{ $orphanedChild->name }}
                    </span>
                  </a>
                @endforeach
              </div>
            @endif

            @if ($postCategories->isEmpty())
              <span class="badge bg-light text-muted rounded-pill me-1 py-2 px-3">
                <i class="bi bi-dash-circle me-1"></i>No Categories
              </span>
            @endif
          </div>

          <hr>

          <!-- Post Content -->
          <div class="card-text lh-lg fs-5 mb-5 post-content">
            {!! $post->desc !!}
          </div>

          <!-- Post Media Files (if they exist) -->
          @if ($post->video || $post->audio)
            <div class="post-media mb-5">
              @if ($post->video)
                <div class="mb-4">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="text-muted mb-0">
                      <i class="bi bi-camera-video-fill me-2 text-primary"></i>Video
                    </h5>
                  </div>

                  <!-- Video Container -->
                  <div class="video-container rounded-4 shadow-sm overflow-hidden">
                    @php
                      // Improved YouTube URL pattern
                      $youtubePattern =
                          '~(?:https?://)?(?:www\.)?(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})~i';
                      $isYouTubeVideo = preg_match($youtubePattern, $post->video, $matches);
                      $youtubeVideoId = $isYouTubeVideo ? $matches[1] : null;
                    @endphp

                    @if ($isYouTubeVideo && $youtubeVideoId)
                      <!-- YouTube Embed -->
                      <div class="ratio ratio-16x9">
                        <iframe
                          src="https://www.youtube.com/embed/{{ $youtubeVideoId }}?rel=0&showinfo=0&autohide=1&modestbranding=1"
                          title="{{ $post->title }}" frameborder="0"
                          allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                          allowfullscreen loading="lazy">
                        </iframe>
                      </div>
                    @else
                      <!-- Native Video Player for uploaded videos -->
                      <video id="native-video" class="w-100 native-video" controls preload="metadata"
                        poster="{{ $post->image ? asset('storage/' . $post->image) : '' }}">
                        <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4" />
                        Your browser does not support HTML5 video.
                      </video>
                    @endif
                  </div>

                  @if (!$isYouTubeVideo)
                    <!-- Custom controls - only for native videos -->
                    <div class="custom-video-controls p-3 bg-light mt-1 rounded-bottom">
                      <div class="d-flex justify-content-between align-items-center">
                        <!-- Time display -->
                        <div class="time-display">
                          <span id="current-position">0:00</span> / <span id="total-duration">0:00</span>
                        </div>

                        <!-- Playback controls -->
                        <div class="playback-controls d-flex align-items-center">
                          <!-- Speed selector -->
                          <div class="me-3">
                            <select id="speed-selector" class="form-select form-select-sm">
                              <option value="0.5">0.5x</option>
                              <option value="0.75">0.75x</option>
                              <option value="1" selected>1x (Normal)</option>
                              <option value="1.25">1.25x</option>
                              <option value="1.5">1.5x</option>
                              <option value="2">2x</option>
                            </select>
                          </div>

                          <!-- Fullscreen button -->
                          <button id="fullscreen-btn" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-fullscreen"></i>
                          </button>
                        </div>
                      </div>
                    </div>

                    <!-- Custom jump buttons - only for native videos -->
                    <div class="jump-controls mt-3 d-flex justify-content-center">
                      <button class="btn btn-sm btn-outline-secondary me-2" data-jump="-30">
                        <i class="bi bi-arrow-counterclockwise"></i> 30s
                      </button>
                      <button class="btn btn-sm btn-outline-secondary me-2" data-jump="-10">
                        <i class="bi bi-arrow-counterclockwise"></i> 10s
                      </button>
                      <button class="btn btn-sm btn-outline-secondary me-2" data-jump="10">
                        <i class="bi bi-arrow-clockwise"></i> 10s
                      </button>
                      <button class="btn btn-sm btn-outline-secondary" data-jump="30">
                        <i class="bi bi-arrow-clockwise"></i> 30s
                      </button>
                    </div>
                  @endif
                </div>
              @endif

              @if ($post->audio)
                <!-- Audio player -->
                <div class="mb-4">
                  <h5 class="text-muted mb-3">
                    <i class="bi bi-music-note-beamed me-2 text-success"></i>Audio
                  </h5>
                  <div class="audio-container rounded-4 shadow-sm p-3 bg-light">
                    <audio id="native-audio" class="w-100" controls preload="metadata">
                      <source src="{{ asset('storage/' . $post->audio) }}" type="audio/mp3" />
                      Your browser does not support HTML5 audio.
                    </audio>

                    <div class="mt-3">
                      <div class="d-flex align-items-center">
                        <label for="audio-speed" class="me-2 text-muted small">Playback Speed:</label>
                        <select id="audio-speed" class="form-select form-select-sm" style="width: auto;">
                          <option value="0.5">0.5x</option>
                          <option value="0.75">0.75x</option>
                          <option value="1" selected>1x (Normal)</option>
                          <option value="1.25">1.25x</option>
                          <option value="1.5">1.5x</option>
                          <option value="2">2x</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
              @endif
            </div>
          @endif

          <!-- Post Navigation -->
          <div class="border-top pt-4 mt-5">
            <div class="row">
              <div class="col-md-6 mb-3 mb-md-0">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary">
                  <i class="bi bi-arrow-left me-1"></i> Back to Posts
                </a>
              </div>

              @if (Auth::check())
                <div class="col-md-6 text-md-end">
                  <a href="{{ route('admin.posts.edit', $post->id) }}" class="btn btn-primary">
                    <i class="bi bi-pencil-square me-1"></i> Edit Post
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Related Posts Section -->
      @if ($relatedPosts->isNotEmpty())
        <div class="related-posts mb-5">
          <h4 class="mb-4 fw-bold"><i class="bi bi-link-45deg me-2"></i>Related Posts</h4>

          <div class="row g-4">
            @foreach ($relatedPosts as $relatedPost)
              <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm hover-card rounded-4 overflow-hidden">
                  <div class="img-hover-zoom">
                    @if ($relatedPost->image)
                      <img src="{{ asset('storage/' . $relatedPost->image) }}" class="card-img-top related-post-img"
                        alt="{{ $relatedPost->title }}" loading="lazy">
                    @else
                      <div class="bg-light text-center py-5">
                        <i class="bi bi-journal-text text-secondary" style="font-size: 2rem;"></i>
                      </div>
                    @endif
                  </div>
                  <div class="card-body p-4">
                    <h5 class="card-title fw-semibold mb-3">
                      <a href="{{ route('posts.show', $relatedPost->id) }}"
                        class="text-decoration-none text-dark stretched-link">
                        {{ Str::limit($relatedPost->title, 50) }}
                      </a>
                    </h5>

                    <!-- Show parent and child categories -->
                    @php
                      // Get parent category
                      $relatedParentCategory = $relatedPost->categories->first(function ($category) {
                          return is_null($category->parent_id);
                      });
                      // If no parent found, use any category
                      if (!$relatedParentCategory && $relatedPost->categories->isNotEmpty()) {
                          $relatedParentCategory = $relatedPost->categories->first();
                      }
                      // Get a child category if exists
                      $relatedChildCategory = null;
                      if ($relatedParentCategory) {
                          $relatedChildCategory = $relatedPost->categories->first(function ($category) use (
                              $relatedParentCategory,
                          ) {
                              return $category->parent_id == $relatedParentCategory->id;
                          });
                      }
                    @endphp

                    <div class="mb-2">
                      @if ($relatedParentCategory)
                        <a href="{{ route('posts.category', $relatedParentCategory->id) }}"
                          class="text-decoration-none">
                          <span class="badge bg-primary text-white rounded-pill me-1">
                            {{ $relatedParentCategory->name }}
                          </span>
                        </a>
                      @endif

                      @if ($relatedChildCategory)
                        <a href="{{ route('posts.category', $relatedChildCategory->id) }}"
                          class="text-decoration-none">
                          <span class="badge bg-light text-primary rounded-pill border border-primary-subtle">
                            {{ $relatedChildCategory->name }}
                          </span>
                        </a>
                      @endif
                    </div>

                    <p class="card-text text-muted small mt-2">
                      {{ Str::limit(strip_tags($relatedPost->desc), 80) }}
                    </p>

                    <div class="d-flex align-items-center mt-3 text-muted small">
                      <i class="bi bi-calendar3 me-1"></i>
                      {{ $relatedPost->created_at->format('M d, Y') }}
                    </div>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      @endif
    </div>
  </div>
@endsection

@push('styles')
  <style>
    /* Dropdown Menu Styling */
    .dropdown-toggle::after {
      display: none;
    }

    .dropdown-menu {
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    /* Category Badge Styling */
    .badge {
      font-weight: 500;
      transition: all 0.2s ease;
    }

    .badge:hover {
      transform: translateY(-2px);
    }

    .parent-category {
      border: none;
    }

    /* Post Content Styling */
    .card-text img {
      max-width: 100%;
      height: auto;
      border-radius: 0.5rem;
      margin: 1.5rem 0;
    }

    .card-text h1,
    .card-text h2,
    .card-text h3,
    .card-text h4,
    .card-text h5,
    .card-text h6 {
      margin-top: 1.5rem;
      margin-bottom: 1rem;
      font-weight: 600;
    }

    .card-text a {
      color: #0d6efd;
      text-decoration: none;
    }

    .card-text a:hover {
      text-decoration: underline;
    }

    .card-text blockquote {
      border-left: 4px solid #0d6efd;
      padding-left: 1rem;
      margin-left: 0;
      color: #6c757d;
      font-style: italic;
    }

    .card-text pre {
      background-color: #f8f9fa;
      border-radius: 0.5rem;
      padding: 1rem;
      margin: 1rem 0;
      overflow-x: auto;
    }

    .card-text code {
      background-color: #f8f9fa;
      padding: 0.2rem 0.4rem;
      border-radius: 0.25rem;
      font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    }

    .card-text table {
      width: 100%;
      margin-bottom: 1rem;
      border-collapse: collapse;
    }

    .card-text table th,
    .card-text table td {
      padding: 0.75rem;
      border: 1px solid #dee2e6;
    }

    .card-text table th {
      background-color: #f8f9fa;
    }

    /* Featured Image Styling */
    .featured-image-container {
      margin-top: -1rem;
      margin-bottom: 2rem;
      position: relative;
    }

    .featured-image {
      max-height: 500px;
      width: auto;
      object-fit: contain;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    /* Related Posts Styling */
    .hover-card {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
    }

    .img-hover-zoom {
      overflow: hidden;
      height: 160px;
    }

    .img-hover-zoom img {
      transition: transform 0.5s ease;
      height: 100%;
      object-fit: cover;
      width: 100%;
    }

    .hover-card:hover .img-hover-zoom img {
      transform: scale(1.05);
    }

    .related-post-img {
      height: 160px;
      object-fit: cover;
    }

    /* Video Container */
    .video-container {
      position: relative;
      padding-bottom: 56.25%;
      /* 16:9 ratio */
      height: 0;
      overflow: hidden;
      border-radius: 0.5rem;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    .video-container video {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
    }

    .native-video {
      display: block;
      width: 100%;
      height: auto;
      max-height: 70vh;
    }

    /* YouTube embed styling */
    .ratio-16x9 {
      aspect-ratio: 16 / 9;
    }

    .ratio {
      position: relative;
      width: 100%;
    }

    .ratio>* {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    /* Custom controls styling */
    .custom-video-controls {
      border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    /* Jump buttons */
    .jump-controls {
      flex-wrap: wrap;
    }

    .jump-controls .btn {
      margin-bottom: 0.5rem;
    }

    /* Time display */
    .time-display {
      font-family: monospace;
      font-size: 0.95rem;
    }

    /* Audio container */
    .audio-container {
      border: 1px solid rgba(0, 0, 0, 0.1);
    }

    audio {
      border-radius: 0.5rem;
      box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
    }

    /* Speed selector */
    #speed-selector,
    #audio-speed {
      width: 120px;
    }

    /* Current time badge */
    .badge.current-time {
      min-width: 85px;
    }

    /* Media queries */
    @media (max-width: 576px) {
      .jump-controls {
        justify-content: space-between;
      }

      .jump-controls .btn {
        margin-right: 0 !important;
        flex-grow: 1;
      }

      .custom-video-controls {
        flex-direction: column;
      }

      .custom-video-controls>div {
        margin-bottom: 0.5rem;
      }
    }
  </style>
@endpush

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Check if we have a native video player
      const video = document.getElementById('native-video');

      if (video) {
        // Get UI elements
        const currentPosition = document.getElementById('current-position');
        const totalDuration = document.getElementById('total-duration');
        const speedSelector = document.getElementById('speed-selector');
        const fullscreenBtn = document.getElementById('fullscreen-btn');
        const jumpButtons = document.querySelectorAll('.jump-controls button');

        // Wait for metadata to load to get duration
        video.addEventListener('loadedmetadata', function() {
          // Set initial values
          totalDuration.textContent = formatTime(video.duration);
          currentPosition.textContent = formatTime(video.currentTime);

          // Enable functionality now that metadata is loaded
          enableVideoControls();
        });

        // Fallback in case metadata doesn't load properly
        setTimeout(() => {
          if (totalDuration && totalDuration.textContent === '0:00') {
            enableVideoControls();
          }
        }, 1000);

        function enableVideoControls() {
          // Update current time display when time updates
          video.addEventListener('timeupdate', function() {
            if (currentPosition) {
              currentPosition.textContent = formatTime(video.currentTime);
            }
          });

          // Update playback speed
          if (speedSelector) {
            speedSelector.addEventListener('change', function() {
              video.playbackRate = parseFloat(this.value);
            });
          }

          // Fullscreen functionality
          if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', function() {
              if (video.requestFullscreen) {
                video.requestFullscreen();
              } else if (video.webkitRequestFullscreen) {
                /* Safari */
                video.webkitRequestFullscreen();
              } else if (video.msRequestFullscreen) {
                /* IE11 */
                video.msRequestFullscreen();
              }
            });
          }

          // Jump buttons functionality
          if (jumpButtons) {
            jumpButtons.forEach(button => {
              button.addEventListener('click', function() {
                const jumpSeconds = parseInt(this.getAttribute('data-jump'));

                // Store current position
                const currentTime = video.currentTime;

                // Calculate new position
                let newTime = currentTime + jumpSeconds;

                // Ensure within bounds
                if (newTime < 0) newTime = 0;
                if (newTime > video.duration) newTime = video.duration;

                // Set the new time
                video.currentTime = newTime;

                // Verify the seeking worked
                setTimeout(() => {
                  // If seeking failed, try again
                  if (Math.abs(video.currentTime - newTime) > 1) {
                    video.currentTime = newTime;
                  }

                  // Update display
                  if (currentPosition) {
                    currentPosition.textContent = formatTime(video.currentTime);
                  }
                }, 50);
              });
            });
          }

          // Add keyboard shortcuts
          document.addEventListener('keydown', function(e) {
            // Only if video is in focus
            if (document.activeElement === video || video.contains(document.activeElement)) {
              switch (e.key) {
                case 'ArrowLeft':
                  // Back 10 seconds
                  video.currentTime = Math.max(0, video.currentTime - 10);
                  break;
                case 'ArrowRight':
                  // Forward 10 seconds
                  video.currentTime = Math.min(video.duration, video.currentTime + 10);
                  break;
                case ' ':
                  // Play/pause
                  if (video.paused) {
                    video.play();
                  } else {
                    video.pause();
                  }
                  e.preventDefault();
                  break;
              }
            }
          });

          // Double-click for fullscreen
          video.addEventListener('dblclick', function() {
            if (document.fullscreenElement) {
              document.exitFullscreen();
            } else {
              if (video.requestFullscreen) {
                video.requestFullscreen();
              }
            }
          });
        }
      }

      // Audio player functionality
      const audio = document.getElementById('native-audio');

      if (audio) {
        const audioSpeed = document.getElementById('audio-speed');

        // Update playback speed
        if (audioSpeed) {
          audioSpeed.addEventListener('change', function() {
            audio.playbackRate = parseFloat(this.value);
          });
        }
      }

      // Helper function to format time
      function formatTime(seconds) {
        if (isNaN(seconds) || seconds === Infinity) return '0:00';

        const min = Math.floor(seconds / 60);
        const sec = Math.floor(seconds % 60);
        return `${min}:${sec.toString().padStart(2, '0')}`;
      }

      // Live clock update
      function updateClock() {
        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');

        const currentTimeElements = document.querySelectorAll('.current-time');
        currentTimeElements.forEach(el => {
          el.innerHTML = `<i class="bi bi-clock me-1"></i>${hours}:${minutes}:${seconds}`;
        });
      }

      // Update the clock every second
      updateClock();
      setInterval(updateClock, 1000);
    });
  </script>
@endpush
