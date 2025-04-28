@extends('layouts.app')
@section('title', 'Create Category')

@section('content')
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="{{ route('admin.category.index') }}">Categories</a></li>
          <li class="breadcrumb-item active" aria-current="page">Create Category</li>
        </ol>
      </nav>

      <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-primary bg-gradient text-white py-3">
          <div class="d-flex align-items-center">
            <i class="bi bi-tag-fill fs-4 me-2"></i>
            <h4 class="card-title mb-0">Create New Category</h4>
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

          <form action="{{ route('admin.category.store') }}" method="POST" class="needs-validation" novalidate>
            @csrf

            <div class="mb-4">
              <label for="name" class="form-label fw-semibold">Category Name</label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name') }}" placeholder="Enter category name" required>
              @error('name')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="description" class="form-label fw-semibold">Description</label>
              <textarea name="description" id="description" rows="3"
                class="form-control @error('description') is-invalid @enderror" placeholder="Enter category description">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="parent_id" class="form-label fw-semibold">Parent Category</label>
              <select name="parent_id" id="parent_id" class="form-select @error('parent_id') is-invalid @enderror">
                <option value="">-- None (Top Level) --</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              @error('parent_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <div class="form-text">Select a parent to create a subcategory</div>
            </div>

            <div class="mb-4">
              <label for="order" class="form-label fw-semibold">Display Order</label>
              <input type="number" name="order" id="order"
                class="form-control @error('order') is-invalid @enderror" value="{{ old('order', 0) }}" min="0">
              @error('order')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
              <div class="form-text">Lower numbers appear first</div>
            </div>

            <div class="mb-4">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                  {{ old('is_active', true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_active">
                  Active Category
                </label>
              </div>
              <div class="form-text">Inactive categories won't be shown on the site</div>
            </div>

            <div class="d-flex justify-content-between mt-4 pt-2">
              <a href="{{ route('admin.category.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Back to Categories
              </a>
              <button type="submit" class="btn btn-primary px-4 py-2">
                <i class="bi bi-check-lg me-1"></i> Create Category
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
