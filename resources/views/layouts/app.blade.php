<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>@yield('title') - Blog</title>

  <!-- Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">

  <!-- Base Styles -->
  <style>
    :root {
      --bs-primary-rgb: 13, 110, 253;
      --bs-primary-light-rgb: 232, 240, 254;
      --bs-secondary-rgb: 108, 117, 125;
      --bs-success-rgb: 25, 135, 84;
      --bs-success-light-rgb: 209, 231, 221;
      --bs-danger-rgb: 220, 53, 69;
      --bs-danger-light-rgb: 248, 215, 218;
      --bs-warning-rgb: 255, 193, 7;
      --bs-info-rgb: 13, 202, 240;
      --bs-light-rgb: 248, 249, 250;
      --bs-dark-rgb: 33, 37, 41;
    }

    body {
      font-family: 'Inter', sans-serif;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: #333;
      background-color: #f8f9fa;
    }

    .content-wrapper {
      flex: 1;
      padding: 1.5rem 0;
    }

    footer {
      padding: 1.5rem 0;
      margin-top: auto;
      background-color: #fff;
      border-top: 1px solid #e9ecef;
    }

    .card {
      transition: all 0.25s ease;
      border-radius: 0.5rem;
    }

    .card:hover {
      box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1) !important;
    }

    .btn {
      border-radius: 0.375rem;
      padding: 0.5rem 1.25rem;
      font-weight: 500;
    }

    .btn-primary {
      background-color: rgba(var(--bs-primary-rgb), 1);
      border-color: rgba(var(--bs-primary-rgb), 0.8);
    }

    .btn-success {
      background-color: rgba(var(--bs-success-rgb), 1);
      border-color: rgba(var(--bs-success-rgb), 0.8);
    }

    .btn-danger {
      background-color: rgba(var(--bs-danger-rgb), 1);
      border-color: rgba(var(--bs-danger-rgb), 0.8);
    }

    .form-control:focus,
    .form-check-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(var(--bs-primary-rgb), 0.15);
    }

    .badge {
      font-weight: 500;
      padding: 0.35em 0.65em;
      color: rgb(61, 61, 235);
    }

    .bg-primary-subtle {
      background-color: rgba(var(--bs-primary-light-rgb), 1) !important;
    }

    .text-primary-emphasis {
      color: rgba(var(--bs-primary-rgb), 0.85) !important;
    }

    .object-fit-cover {
      object-fit: cover;
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
  </style>

  <!-- Additional Page Styles -->
  @stack('styles')
</head>

<body>
  <!-- Navigation -->
  <x-nav-bar></x-nav-bar>

  <!-- Main Content -->
  <main class="content-wrapper">
    <div class="container">
      @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
          <i class="bi bi-check-circle-fill me-2 fs-5"></i>
          <div>{{ session('success') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center mb-4" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
          <div>{{ session('error') }}</div>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @yield('content')
    </div>
  </main>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="d-flex justify-content-center align-items-center">
        © 2025 <strong> Blog.</strong> All rights reserved.
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="{{ asset('js/main.js') }}"></script>

  <!-- Additional Page Scripts -->
  @stack('scripts')
</body>

</html>
