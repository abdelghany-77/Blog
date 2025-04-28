@extends('layouts.app')
@section('title', 'Categories')

@section('content')
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h1 class="fs-2 fw-bold">Categories</h1>
      <a href="{{ route('admin.category.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> Create Category
      </a>
    </div>

    <div class="card shadow-sm border-0">
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="table-light">
              <tr>
                <th scope="col">Name</th>
                <th scope="col">Description</th>
                <th scope="col">Posts</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-end">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($categories as $category)
                @include('admin.category.partials.category-row', ['category' => $category, 'level' => 0])
              @endforeach

              @if ($categories->isEmpty())
                <tr>
                  <td colspan="5" class="text-center py-4">
                    <i class="bi bi-tag fs-1 text-muted"></i>
                    <p class="mt-2">No categories found</p>
                    <a href="{{ route('admin.category.create') }}" class="btn btn-sm btn-outline-primary">
                      Create your first category
                    </a>
                  </td>
                </tr>
              @endif
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
