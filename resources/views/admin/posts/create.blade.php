@extends('layouts.app')
@section('title', 'Create Post')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <div class="card shadow border-0 rounded-4 mb-4">
        <div class="card-header bg-primary bg-gradient text-white py-3">
          <div class="d-flex align-items-center">
            <i class="bi bi-file-earmark-plus fs-4 me-2"></i>
            <h4 class="card-title mb-0">Create New Post</h4>
          </div>
        </div>
        <div class="card-body p-4">
          @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
              <div class="d-flex align-items-center">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                <strong>Please fix the following errors:</strong>
              </div>
              <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
          @endif

          <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data"
            class="needs-validation" novalidate>
            @csrf

            <div class="mb-4">
              <label for="title" class="form-label fw-semibold">Post Title</label>
              <div class="input-group">
                <span class="input-group-text bg-light">
                  <i class="bi bi-type-h1"></i>
                </span>
                <input type="text" name="title" id="title"
                  class="form-control form-control-lg @error('title') is-invalid @enderror" value="{{ old('title') }}"
                  placeholder="Enter post title" required>
              </div>
              @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="desc" class="form-label fw-semibold">Post Description</label>
              <textarea name="desc" id="desc" class="form-control @error('desc') is-invalid @enderror" required>{{ old('desc') }}</textarea>
              @error('desc')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <div class="form-text">Format your content with the editor tools above.</div>
            </div>

            <div class="card bg-light border mb-4">
              <div class="card-header bg-light">
                <h5 class="mb-0">Media Files</h5>
              </div>
              <div class="card-body">
                <div class="row g-3">
                  <div class="col-md-4">
                    <label for="image" class="form-label fw-semibold d-flex align-items-center">
                      <i class="bi bi-image me-2"></i> Image
                    </label>
                    <input type="file" name="image" id="image"
                      class="form-control @error('image') is-invalid @enderror" accept="image/*">
                    <div class="form-text">Supported formats: JPG, PNG, WebP</div>
                    @error('image')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>

                  <!-- Video Upload or YouTube URL -->
<div class="mb-4">
  <label class="form-label fw-semibold">Video</label>

  <!-- Video Type Selection Tabs -->
  <ul class="nav nav-tabs mb-3" id="videoTypeTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="upload-video-tab" data-bs-toggle="tab" data-bs-target="#upload-video"
        type="button" role="tab" aria-controls="upload-video" aria-selected="true">
        <i class="bi bi-upload me-1"></i> Upload Video
      </button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="youtube-video-tab" data-bs-toggle="tab" data-bs-target="#youtube-video"
        type="button" role="tab" aria-controls="youtube-video" aria-selected="false">
        <i class="bi bi-youtube me-1"></i> YouTube Video
      </button>
    </li>
  </ul>

  <!-- Tab Content -->
  <div class="tab-content" id="videoTabContent">
    <!-- Upload Video Tab -->
    <div class="tab-pane fade show active" id="upload-video" role="tabpanel" aria-labelledby="upload-video-tab">
      <div class="input-group">
        <input type="file" class="form-control" id="video" name="video" accept="video/mp4,video/mov,video/avi,video/webm">
      </div>
      <div class="form-text">
        Upload MP4, MOV, AVI or WebM file (max 20MB).
      </div>
    </div>

    <!-- YouTube Video Tab -->
    <div class="tab-pane fade" id="youtube-video" role="tabpanel" aria-labelledby="youtube-video-tab">
      <div class="input-group">
        <span class="input-group-text"><i class="bi bi-youtube"></i></span>
        <input type="url" class="form-control" id="youtube_video" name="youtube_video"
          placeholder="https://www.youtube.com/watch?v=XXXXXXXXXXX">
      </div>
      <div class="form-text">
        Enter a YouTube video URL (e.g., https://www.youtube.com/watch?v=XXXXXXXXXXX).
      </div>

      <!-- YouTube Preview -->
      <div id="youtube-preview" class="mt-3" style="display: none;">
        <label class="form-label">Preview:</label>
        <div class="ratio ratio-16x9" style="max-width: 400px;">
          <iframe id="youtube-preview-frame" src="" title="YouTube video preview"
            frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
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
                    <label for="audio" class="form-label fw-semibold d-flex align-items-center">
                      <i class="bi bi-music-note-beamed me-2"></i> Audio
                    </label>
                    <input type="file" name="audio" id="audio"
                      class="form-control @error('audio') is-invalid @enderror" accept="audio/*">
                    <div class="form-text">Supported formats: MP3, WAV</div>
                    @error('audio')
                      <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label fw-semibold d-flex align-items-center mb-3">
                <i class="bi bi-tags me-2"></i> Categories
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
                                {{ in_array($subCategory->id, old('category_ids', [])) ? 'checked' : '' }}>
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

            <div class="d-flex justify-content-between mt-4 pt-2">
              <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Posts
              </a>
              <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bi bi-check-lg me-1"></i> Create Post
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection

@push('styles')
  <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .form-control:focus,
    .form-check-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
      border-color: #86b7fe;
    }

    .card {
      transition: all 0.2s ease;
    }

    .custom-checkbox .form-check-input:checked {
      background-color: #0d6efd;
      border-color: #0d6efd;
    }

    .input-group-text {
      border-right: none;
    }

    .input-group .form-control {
      border-left: none;
    }

    .input-group .form-control:focus {
      border-left: 1px solid #86b7fe;
    }

    .card-header {
      border-bottom: none;
    }

    .form-check-input {
      cursor: pointer;
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
          ['color', ['color'] ],
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

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // YouTube URL validation and preview
    const youtubeInput = document.getElementById('youtube_video');
    const youtubePreview = document.getElementById('youtube-preview');
    const youtubePreviewFrame = document.getElementById('youtube-preview-frame');

    if (youtubeInput) {
      youtubeInput.addEventListener('input', function() {
        const youtubeUrl = this.value.trim();

        // YouTube URL patterns
        const patterns = [
          /(?:https?:\/\/)?(?:www\.)?youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/, // Standard YouTube URL
          /(?:https?:\/\/)?(?:www\.)?youtu\.be\/([a-zA-Z0-9_-]{11})/, // Shortened youtu.be URL
          /(?:https?:\/\/)?(?:www\.)?youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/ // Embed URL
        ];

        let videoId = null;

        // Check each pattern to find a match
        for (const pattern of patterns) {
          const match = youtubeUrl.match(pattern);
          if (match && match[1]) {
            videoId = match[1];
            break;
          }
        }

        if (videoId) {
          // Valid YouTube URL - show preview
          youtubePreviewFrame.src = `https://www.youtube.com/embed/${videoId}`;
          youtubePreview.style.display = 'block';
        } else {
          // Not a valid YouTube URL - hide preview
          youtubePreview.style.display = 'none';
        }
      });
    }

    // Tab switching - clear other field when switching tabs
    const videoTabs = document.querySelectorAll('#videoTypeTabs .nav-link');
    const videoFileInput = document.getElementById('video');

    if (videoTabs.length > 0 && videoFileInput && youtubeInput) {
      videoTabs.forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(event) {
          if (event.target.id === 'upload-video-tab') {
            // Switched to upload tab, clear YouTube field
            youtubeInput.value = '';
            youtubePreview.style.display = 'none';
          } else if (event.target.id === 'youtube-video-tab') {
            // Switched to YouTube tab, clear file upload
            videoFileInput.value = '';
          }
        });
      });
    }
  });
</script>
@endpush
