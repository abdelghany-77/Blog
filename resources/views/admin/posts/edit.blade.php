@extends('layouts.app')
@section('title', 'Update Post')

@section('content')
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card shadow-sm border-0 rounded-3 mb-4">
          <div class="card-header bg-success bg-gradient text-white py-3">
            <h4 class="card-title mb-0">Edit Post: {{ $post->title }}</h4>
          </div>
          <div class="card-body p-4">
            @if ($errors->any())
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following errors:</strong>
                <ul class="mb-0 mt-2">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif

            <form action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')

              <div class="mb-4">
                <label for="title" class="form-label fw-bold">Post Title</label>
                <input type="text" name="title" id="title"
                  class="form-control form-control-lg @error('title') is-invalid @enderror"
                  value="{{ old('title', $post->title) }}" placeholder="Enter post title" required>
                @error('title')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-4">
                <label for="desc" class="form-label fw-semibold">Post Description</label>
                <textarea name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror" required>{{ old('desc', $post->desc) }}</textarea>
                @error('desc')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
                <div class="form-text">Format your content with the editor tools above.</div>
              </div>

              <div class="row mb-4">
                <div class="col-md-4 mb-3 mb-md-0">
                  <label for="image" class="form-label fw-bold">
                    <i class="fas fa-image me-1"></i> Image
                  </label>
                  <input type="file" name="image" id="image"
                    class="form-control @error('image') is-invalid @enderror" accept="image/*">
                  @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  @if ($post->image)
                    <div class="mt-2">
                      <div class="card border-0">
                        <img src="{{ asset('storage/' . $post->image) }}" alt="Current Image" class="img-thumbnail"
                          style="max-height: 150px; width: auto;">
                        <div class="card-img-overlay d-flex justify-content-end align-items-start p-0">
                          <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" id="keepImage"
                              name="keep_image" checked>
                            <label class="form-check-label text-white bg-dark bg-opacity-75 px-2 rounded-pill"
                              for="keepImage">Keep</label>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>

                <!-- Video Upload or YouTube URL -->
                <div class="mb-4">
                  <label class="form-label fw-semibold">Video</label>

                  @php
                    // Check if the video is a YouTube URL
                    $youtubePattern =
                        '/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:embed\/|watch\?v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
                    $isYouTubeVideo = $post->video && preg_match($youtubePattern, $post->video, $matches);
                    $youtubeVideoId = $isYouTubeVideo ? $matches[1] : null;
                  @endphp

                  <!-- Video Type Selection Tabs -->
                  <ul class="nav nav-tabs mb-3" id="videoTypeTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                      <button class="nav-link {{ !$isYouTubeVideo ? 'active' : '' }}" id="upload-video-tab"
                        data-bs-toggle="tab" data-bs-target="#upload-video" type="button" role="tab"
                        aria-controls="upload-video" aria-selected="{{ !$isYouTubeVideo ? 'true' : 'false' }}">
                        <i class="bi bi-upload me-1"></i> Upload Video
                      </button>
                    </li>
                    <li class="nav-item" role="presentation">
                      <button class="nav-link {{ $isYouTubeVideo ? 'active' : '' }}" id="youtube-video-tab"
                        data-bs-toggle="tab" data-bs-target="#youtube-video" type="button" role="tab"
                        aria-controls="youtube-video" aria-selected="{{ $isYouTubeVideo ? 'true' : 'false' }}">
                        <i class="bi bi-youtube me-1"></i> YouTube Video
                      </button>
                    </li>
                  </ul>

                  <!-- Tab Content -->
                  <div class="tab-content" id="videoTabContent">
                    <!-- Upload Video Tab -->
                    <div class="tab-pane fade {{ !$isYouTubeVideo ? 'show active' : '' }}" id="upload-video"
                      role="tabpanel" aria-labelledby="upload-video-tab">
                      <div class="input-group">
                        <input type="file" class="form-control" id="video" name="video"
                          accept="video/mp4,video/mov,video/avi,video/webm">
                      </div>
                      <div class="form-text">
                        Upload MP4, MOV, AVI or WebM file (max 20MB).
                      </div>

                      @if ($post->video && !$isYouTubeVideo)
                        <div class="mt-2">
                          <label class="form-label">Current Video:</label>
                          <div>
                            <video controls class="mt-2 rounded" style="max-width: 400px; max-height: 225px;">
                              <source src="{{ asset('storage/' . $post->video) }}" type="video/mp4">
                              Your browser does not support the video tag.
                            </video>
                          </div>
                        </div>
                      @endif
                    </div>

                    <!-- YouTube Video Tab -->
                    <div class="tab-pane fade {{ $isYouTubeVideo ? 'show active' : '' }}" id="youtube-video"
                      role="tabpanel" aria-labelledby="youtube-video-tab">
                      <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-youtube"></i></span>
                        <input type="url" class="form-control" id="youtube_video" name="youtube_video"
                          placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX"
                          value="{{ $isYouTubeVideo ? $post->video : '' }}">
                      </div>
                      <div class="form-text">
                        Enter a YouTube video URL (e.g., https://www.youtube.com/watch?v=XXXXXXXXXXX).
                      </div>

                      <!-- YouTube Preview -->
                      <div id="youtube-preview" class="mt-3" style="{{ $isYouTubeVideo ? '' : 'display: none;' }}">
                        <label class="form-label">Preview:</label>
                        <div class="ratio ratio-16x9" style="max-width: 400px;">
                          <iframe id="youtube-preview-frame"
                            src="{{ $isYouTubeVideo ? 'https://www.youtube.com/embed/' . $youtubeVideoId : '' }}"
                            title="YouTube video preview" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen></iframe>
                        </div>
                      </div>
                    </div>
                  </div>

                  @error('video')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror

                  @error('youtube_video')
                    <div class="text-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4">
                  <label for="audio" class="form-label fw-bold">
                    <i class="fas fa-music me-1"></i> Audio
                  </label>
                  <input type="file" name="audio" id="audio"
                    class="form-control @error('audio') is-invalid @enderror" accept="audio/*">
                  @error('audio')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                  @if ($post->audio ?? false)
                    <div class="mt-2">
                      <div class="d-flex align-items-center">
                        <i class="fas fa-file-audio fs-4 me-2 text-muted"></i>
                        <span class="small">Current audio file</span>
                        <div class="form-check form-switch ms-auto">
                          <input class="form-check-input" type="checkbox" role="switch" id="keepAudio"
                            name="keep_audio" checked>
                          <label class="form-check-label" for="keepAudio">Keep</label>
                        </div>
                      </div>
                    </div>
                  @endif
                </div>
                <div class="mb-4">
                  <label class="form-label fw-semibold d-flex align-items-center mb-3">
                    <i class="fas fa-tags me-2"></i> Categories
                  </label>
                  <div class="card border">
                    <div class="card-body">
                      @foreach ($mainCategories as $mainCategory)
                        <h6 class="fw-bold mb-2 mt-3">{{ $mainCategory->name }}</h6>
                        <div class="row g-3 ms-3 mb-3">
                          @if ($mainCategory->children->count() > 0)
                            @foreach ($mainCategory->children as $subCategory)
                              <div class="col-md-4 col-sm-6">
                                <div class="form-check custom-checkbox py-2">
                                  <input class="form-check-input" type="checkbox" name="category_ids[]"
                                    value="{{ $subCategory->id }}" id="category-{{ $subCategory->id }}"
                                    @if (in_array($subCategory->id, old('category_ids', $post->categories->pluck('id')->toArray()))) checked @endif>
                                  <label class="form-check-label" for="category-{{ $subCategory->id }}">
                                    {{ $subCategory->name }}
                                  </label>
                                </div>
                              </div>
                            @endforeach
                          @else
                            <div class="col-12">
                              <p class="text-muted">No subcategories available</p>
                            </div>
                          @endif
                        </div>
                        @if (!$loop->last)
                          <hr>
                        @endif
                      @endforeach
                    </div>
                  </div>
                  <div class="form-text">Select categories to help organize your content</div>
                </div>
              </div>
              <div class="d-flex justify-content-between mt-4 pt-2">
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                  <i class="fas fa-arrow-left me-1"></i> Back to Posts
                </a>
                <button type="submit" class="btn btn-success btn-lg px-4">
                  <i class="fas fa-save me-1"></i> Update Post
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <style>
    .form-control:focus {
      box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15);
    }

    .card {
      transition: all 0.3s ease;
    }

    /* Custom styling for Summernote */
    .note-editor {
      border-radius: 0.375rem;
      border-color: #dee2e6;
    }

    .note-editor.note-frame {
      border: 1px solid #dee2e6;
    }

    .note-editor.note-frame .note-statusbar {
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
    }

    .note-editor.note-frame .note-status-output {
      height: 12px;
    }

    .note-editor .note-toolbar {
      background-color: #f8f9fa;
      border-bottom: 1px solid #dee2e6;
      padding: 5px 10px;
    }

    .note-btn {
      border-radius: 0.25rem;
      padding: 0.25rem 0.5rem;
    }

    .note-btn-group {
      margin-right: 5px;
    }

    /* Properly sized font options */
    .note-editor .dropdown-fontsize .dropdown-menu {
      min-width: 100px;
    }

    /* Making editor content match your theme */
    .note-editable {
      background-color: #fff;
      color: #212529;
      padding: 15px;
      min-height: 200px;
      font-family: inherit;
    }

    /* Focus styling */
    .note-editor.note-frame.focus {
      border-color: #86b7fe;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    /* Error state */
    .is-invalid+.note-editor.note-frame {
      border-color: #dc3545;
    }

    /* Override error feedback to appear correctly with Summernote */
    .invalid-feedback {
      display: block;
    }
  </style>
@endpush

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
  <script>
    $(document).ready(function() {
      $('#desc').summernote({
        placeholder: 'Enter your post description here...',
        height: 300,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold', 'underline', 'clear']],
          ['fontsize', ['fontsize']],
          ['color', ['color']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['table', ['table']],
          ['insert', ['link', 'picture']],
          ['view', ['fullscreen', 'codeview', 'help']]
        ],
        fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '22', '24', '28', '32', '36', '48', '64',
          '82', '96'
        ],
        callbacks: {
          onImageUpload: function(files) {
            // This would be where you handle image uploads
            for (let i = 0; i < files.length; i++) {
              let reader = new FileReader();
              reader.onloadend = function() {
                let image = $('<img>').attr('src', reader.result)
                  .addClass('img-fluid rounded');
                $('#desc').summernote('insertNode', image[0]);
              }
              reader.readAsDataURL(files[i]);
            }
          }
        }
      });
    });
  </script>
@endpush
