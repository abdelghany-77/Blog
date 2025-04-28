@extends('layouts.app')
@section('title', 'Update category')

@section('content')
  <div class="container py-2">
    <div class="row justify-content-center">
      <div class="col-md-8">
        <div class="card mb-4">
          <div class="card-body">
            <h1 class="card-title">Update category</h1>
            @if ($errors->any())
              <div class="alert alert-danger">
                <ul>
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </div>
            @endif
            <form action="{{ route('admin.category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control"
                  value="{{ old('name', $category->name) }}">
              </div>
              <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" name="slug" id="slug" class="form-control"
                  value="{{ old('slug', $category->slug) }}">
              </div>
              <button type="submit" class="btn btn-primary">Update category</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
