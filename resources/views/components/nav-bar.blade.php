<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <!-- Brand Logo with Animation -->
    <a class="navbar-brand fw-bold d-flex align-items-center logo-hover" href="{{ route('home') }}">
      <span class="bg-primary text-white p-1 rounded me-2 d-flex align-items-center justify-content-center logo-icon">
        <i class="bi bi-journal-richtext"></i>
      </span>
      <span class="brand-text">Blog</span>
    </a>

    <!-- Mobile Toggle Button with Animation -->
    <button class="navbar-toggler border-0 custom-toggler" type="button" data-bs-toggle="collapse"
      data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
      aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navigation Items -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('home') ? 'active fw-semibold' : '' }}" href="{{ route('home') }}">
            <i class="bi bi-house-door-fill me-1"></i> Home
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ request()->routeIs('posts.index') ? 'active fw-semibold' : '' }}"
            href="{{ route('posts.index') }}">
            <i class="bi bi-journal-richtext me-1"></i> Posts
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle {{ request()->routeIs('posts.category*') ? 'active fw-semibold' : '' }}"
            href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-tags-fill me-1"></i> Categories
          </a>
          <ul class="dropdown-menu shadow border-0 rounded-3 animate-dropdown dropdown-menu-language"
            aria-labelledby="navbarDropdown">
            @if (!empty($categories) && count($categories) > 0)
              @foreach ($categories->whereNull('parent_id') ?? [] as $parentCategory)
                <li class="dropdown-parent-item">
                  <!-- Parent category with toggle button -->
                  <div class="d-flex align-items-center">
                    <a class="dropdown-item parent-category py-2 px-3 d-flex align-items-center flex-grow-1"
                      href="{{ route('posts.category', $parentCategory->id) }}">
                      <span class="badge category-badge me-2">
                        {{ $parentCategory->posts->count() }}
                      </span>
                      <span class="flex-grow-1 fw-medium">{{ $parentCategory->name }}</span>
                    </a>

                    <!-- Toggle button (only show if has children) -->
                    @if ($parentCategory->children && $parentCategory->children->count() > 0)
                      <button class="btn btn-sm category-toggle px-3 py-1 me-2"
                        data-category="{{ $parentCategory->id }}" aria-expanded="false"
                        aria-label="Toggle subcategories">
                        <i class="bi bi-plus"></i>
                      </button>
                    @endif
                  </div>

                  <!-- Subcategories (initially hidden) -->
                  @if ($parentCategory->children && $parentCategory->children->count() > 0)
                    <ul class="subcategory-list collapse" id="subcategory-{{ $parentCategory->id }}">
                      @foreach ($parentCategory->children as $childCategory)
                        <li>
                          <a class="dropdown-item subcategory-item py-2 px-3 d-flex align-items-center"
                            href="{{ route('posts.category', $childCategory->id) }}">
                            <span class="badge bg-light text-secondary me-2">
                              {{ $childCategory->posts->count() }}
                            </span>
                            <span class="flex-grow-1">{{ $childCategory->name }}</span>
                          </a>
                        </li>
                      @endforeach
                    </ul>
                  @endif
                </li>

                <!-- Add divider between parent categories -->
                @if (!$loop->last)
                  <li>
                    <hr class="dropdown-divider my-1">
                  </li>
                @endif
              @endforeach
            @else
              <li>
                <span class="dropdown-item py-2 px-3 text-muted">
                  <i class="bi bi-info-circle me-2"></i>No categories available
                </span>
              </li>
            @endif

            <li>
              <hr class="dropdown-divider">
            </li>

            <li>
              <a class="dropdown-item py-2 px-3" href="{{ route('admin.category.index') }}">
                <i class="bi bi-grid-3x3-gap-fill me-2 text-primary"></i> Browse All Categories
              </a>
            </li>
          </ul>
        </li>
        @auth
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.posts.create') ? 'active fw-semibold' : '' }} nav-btn"
              href="{{ route('admin.posts.create') }}">
              <i class="bi bi-plus-circle-fill me-1"></i> Create Post
            </a>
          </li>
        @endauth
      </ul>

      <!-- Search Form  -->
      <form class="d-flex ms-auto me-3 search-form position-relative" action="{{ route('posts.search') }}"
        method="GET">
        <div class="input-group search-group">
          <input class="form-control border-end-0 rounded-pill rounded-end search-input" type="search" name="search"
            placeholder="Search posts..." aria-label="Search">
          <button class="btn btn-outline-primary border-start-0 rounded-pill rounded-start search-btn" type="submit">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </form>

      <!-- Authentication Links -->
      <ul class="navbar-nav mb-2 mb-lg-0">
        @auth
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center user-dropdown" href="#" id="userDropdown"
              role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <div
                class="avatar bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center me-2">
                <i class="bi bi-person-fill"></i>
              </div>
              <span class="d-none d-sm-inline">{{ Auth::user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 animate-dropdown"
              aria-labelledby="userDropdown">
              <li class="dropdown-item-text pt-2 pb-2 px-3 user-info">
                <div class="d-flex align-items-center">
                  <div
                    class="avatar bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                    style="width: 40px; height: 40px;">
                    <i class="bi bi-person-fill"></i>
                  </div>
                  <div>
                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                    <div class="text-muted small">{{ Auth::user()->email }}</div>
                  </div>
                </div>
              </li>

              <li>
                <hr class="dropdown-divider">
              </li>

              <li>
                <a class="dropdown-item py-2 px-3" href="{{ route('admin.posts.index') }}">
                  <i class="bi bi-speedometer2 me-2 text-primary"></i> Dashboard
                </a>
              </li>

              <li>
                <a class="dropdown-item py-2 px-3" href="{{ route('admin.posts.create') }}">
                  <i class="bi bi-plus-circle me-2 text-success"></i> New Post
                </a>
              </li>

              <li>
                <hr class="dropdown-divider">
              </li>
              <li>
                <a class="dropdown-item py-2 px-3 text-danger" href="{{ route('logout') }}"
                  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="bi bi-box-arrow-right me-2"></i> Sign Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
        @else
          <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('login') ? 'active fw-semibold' : '' }}"
              href="{{ route('login') }}">
              <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
            </a>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

@section('styles')
  <style>
    :root {
      --transition-speed: 0.3s;
      --hover-bg: rgba(13, 110, 253, 0.04);
      --active-bg: rgba(13, 110, 253, 0.1);
      --hover-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    /* Navbar styling */
    .navbar {
      padding-top: 0.75rem;
      padding-bottom: 0.75rem;
      transition: all var(--transition-speed);
    }

    /* Scrolled navbar effect */
    .navbar.scrolled {
      padding-top: 0.5rem;
      padding-bottom: 0.5rem;
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.1);
    }

    /* Brand and logo styling */
    .logo-icon {
      width: 36px;
      height: 36px;
      transition: transform 0.3s ease, background-color 0.3s;
    }

    .logo-hover:hover .logo-icon {
      transform: rotate(10deg);
      background-color: #0d5bd9 !important;
    }

    .brand-text {
      background: linear-gradient(45deg, #0d6efd, #0d5bd9);
      background-clip: text;
      -webkit-background-clip: text;
      color: transparent;
      font-weight: 700;
    }

    /* Navigation link styling */
    .navbar .nav-link {
      padding: 0.5rem 1rem;
      color: #495057;
      border-radius: 0.375rem;
      transition: all var(--transition-speed);
      position: relative;
    }

    .navbar .nav-link:hover {
      color: #0d6efd;
      background-color: var(--hover-bg);
    }

    .navbar .nav-link.active {
      color: #0d6efd;
      background-color: var(--active-bg);
    }

    .navbar .nav-link.active::after {
      content: '';
      position: absolute;
      bottom: 0px;
      left: 50%;
      transform: translateX(-50%);
      width: 20px;
      height: 3px;
      background-color: #0d6efd;
      border-radius: 5px;
    }

    /* Special styling for create post button */
    .nav-btn {
      transition: all 0.3s;
      border: 1px dashed rgba(13, 110, 253, 0.4);
    }

    .nav-btn:hover {
      border-color: #0d6efd;
      background-color: rgba(13, 110, 253, 0.08);
      transform: translateY(-2px);
    }

    /* Dropdown styling */
    .dropdown-item {
      border-radius: 0.375rem;
      transition: all var(--transition-speed);
      padding: 0.6rem 1rem;
    }

    .dropdown-item:hover {
      background-color: var(--hover-bg);
      transform: translateX(3px);
    }

    .dropdown-item:active {
      background-color: var(--active-bg);
      color: inherit;
    }

    .animate-dropdown {
      animation: growDown 0.3s ease-in-out forwards;
      transform-origin: top center;
    }

    /* Categories dropdown styling */
    .dropdown-menu-language {
      width: 280px;
      max-height: 500px;
      overflow-y: auto;
      padding: 0.5rem;
    }

    .dropdown-parent-item {
      position: relative;
    }

    .parent-category {
      font-weight: 500;
      transition: all 0.2s ease;
      padding-right: 0 !important;
      /* Make room for toggle button */
      border-radius: 0.375rem;
    }

    .parent-category:hover,
    .parent-category:focus {
      background-color: rgba(13, 110, 253, 0.04);
    }

    /* Category toggle button styling */
    .category-toggle {
      background: transparent;
      border: 1px solid rgba(0, 0, 0, 0.1);
      border-radius: 4px;
      transition: all 0.2s ease;
      color: #6c757d;
      margin-top: 2px;
      margin-bottom: 2px;
      line-height: 1;
      min-width: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .category-toggle:hover {
      background-color: rgba(13, 110, 253, 0.1);
      color: #0d6efd;
      border-color: rgba(13, 110, 253, 0.3);
    }

    .category-toggle:focus {
      outline: none;
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .category-toggle[aria-expanded="true"] .bi-plus {
      transform: rotate(45deg);
    }

    .category-toggle .bi-plus {
      transition: transform 0.2s ease;
    }

    /* Subcategories styling */
    .subcategory-list {
      list-style: none;
      padding-left: 0;
      margin-bottom: 0.5rem;
      border-left: 2px solid rgba(13, 110, 253, 0.2);
      margin-left: 1.5rem;
      padding-top: 0.25rem;
      padding-bottom: 0.25rem;
    }

    .subcategory-item {
      padding-left: 2rem !important;
      position: relative;
      font-size: 0.95rem;
      transition: all 0.2s ease;
    }

    .subcategory-item:hover,
    .subcategory-item:focus {
      background-color: rgba(13, 110, 253, 0.04);
      transform: translateX(3px);
    }

    /* Specific category styling - Grammar (blue) */
    .dropdown-parent-item:nth-child(1) .category-badge {
      background-color: rgba(13, 110, 253, 0.1);
      color: #0d6efd;
    }

    .dropdown-parent-item:nth-child(1) .subcategory-list {
      border-left-color: rgba(13, 110, 253, 0.4);
    }

    .dropdown-parent-item:nth-child(1) .category-toggle:hover {
      background-color: rgba(13, 110, 253, 0.1);
      color: #0d6efd;
      border-color: rgba(13, 110, 253, 0.3);
    }

    /* Specific category styling - Listening (green) */
    .dropdown-parent-item:nth-child(3) .category-badge {
      background-color: rgba(25, 135, 84, 0.1);
      color: #198754;
    }

    .dropdown-parent-item:nth-child(3) .subcategory-list {
      border-left-color: rgba(25, 135, 84, 0.4);
    }

    .dropdown-parent-item:nth-child(3) .category-toggle:hover {
      background-color: rgba(25, 135, 84, 0.1);
      color: #198754;
      border-color: rgba(25, 135, 84, 0.3);
    }

    /* Specific category styling - Reading (red) */
    .dropdown-parent-item:nth-child(5) .category-badge {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }

    .dropdown-parent-item:nth-child(5) .subcategory-list {
      border-left-color: rgba(220, 53, 69, 0.4);
    }

    .dropdown-parent-item:nth-child(5) .category-toggle:hover {
      background-color: rgba(220, 53, 69, 0.1);
      color: #dc3545;
      border-color: rgba(220, 53, 69, 0.3);
    }

    /* Animation for subcategories */
    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .subcategory-list.show {
      animation: slideDown 0.3s ease-out forwards;
    }

    /* Custom toggler styling */
    .custom-toggler {
      transition: transform 0.3s;
    }

    .custom-toggler:hover {
      transform: scale(1.1);
    }

    .navbar-toggler:focus {
      box-shadow: none;
      outline: none;
    }

    /* Search form styling */
    .search-input {
      transition: all 0.3s;
      padding-left: 15px;
      padding-right: 15px;
      border-color: #dee2e6;
    }

    .search-input:focus {
      box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
      border-color: #86b7fe;
    }

    .search-btn {
      transition: all 0.3s;
    }

    .search-group:hover .search-btn,
    .search-group:focus-within .search-btn {
      color: #0d6efd;
      background-color: transparent;
    }

    .search-suggestions {
      top: 100%;
      left: 0;
      right: 0;
      z-index: 1000;
      margin-top: 5px;
      border-radius: 0.5rem;
      width: 100%;
      box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    /* User dropdown styling */
    .avatar {
      width: 32px;
      height: 32px;
      transition: all 0.3s;
    }

    .user-dropdown:hover .avatar {
      transform: scale(1.1);
    }

    .user-info {
      transition: background-color 0.3s;
    }

    .user-info:hover {
      background-color: rgba(13, 110, 253, 0.02);
    }

    /* Sign up button styling */
    .btn-sign-up {
      transition: all 0.3s;
      box-shadow: 0 0.125rem 0.25rem rgba(13, 110, 253, 0.2);
    }

    .btn-sign-up:hover {
      transform: translateY(-2px);
      box-shadow: 0 0.5rem 1rem rgba(13, 110, 253, 0.3);
    }

    /* Mobile responsive styles */
    @media (max-width: 991.98px) {
      .navbar-nav {
        padding: 1rem 0;
      }

      .navbar .nav-link {
        padding: 0.75rem 1rem;
      }

      .search-form {
        margin: 1rem 0;
        width: 100%;
      }

      .navbar .nav-link.active::after {
        display: none;
      }

      /* Improved mobile dropdown menu */
      .dropdown-menu-language {
        width: 100%;
        margin-top: 0.5rem;
      }

      /* Make sure toggle buttons are more touch-friendly on mobile */
      .category-toggle {
        min-width: 44px;
        min-height: 38px;
      }

      /* Mobile dropdown animation */
      @keyframes slideIn {
        0% {
          transform: translateY(-10px);
          opacity: 0;
        }

        100% {
          transform: translateY(0);
          opacity: 1;
        }
      }

      .animate-dropdown {
        animation: slideIn 0.2s ease-out forwards;
      }
    }

    /* Dropdown animation */
    @keyframes growDown {
      0% {
        transform: scaleY(0);
        opacity: 0;
      }

      80% {
        transform: scaleY(1.1);
      }

      100% {
        transform: scaleY(1);
        opacity: 1;
      }
    }
  </style>
@endsection

@push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Scroll effect for navbar
      const navbar = document.querySelector('.navbar');

      window.addEventListener('scroll', function() {
        if (window.scrollY > 10) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });

      // Search input focus effect
      const searchInput = document.querySelector('.search-input');
      const searchSuggestions = document.querySelector('.search-suggestions');

      if (searchInput && searchSuggestions) {
        searchInput.addEventListener('focus', function() {
          searchSuggestions.classList.remove('d-none');
        });

        searchInput.addEventListener('blur', function() {
          setTimeout(() => {
            searchSuggestions.classList.add('d-none');
          }, 200);
        });
      }

      // Category toggle functionality
      const categoryToggles = document.querySelectorAll('.category-toggle');

      categoryToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
          e.preventDefault();
          e.stopPropagation();

          const categoryId = this.getAttribute('data-category');
          const subcategoryList = document.getElementById('subcategory-' + categoryId);

          // Toggle the collapse
          if (subcategoryList) {
            if (subcategoryList.classList.contains('show')) {
              subcategoryList.classList.remove('show');
              this.setAttribute('aria-expanded', 'false');
              this.querySelector('i').classList.remove('bi-dash');
              this.querySelector('i').classList.add('bi-plus');
            } else {
              // Close all other open subcategory lists first
              document.querySelectorAll('.subcategory-list.show').forEach(list => {
                if (list.id !== 'subcategory-' + categoryId) {
                  list.classList.remove('show');
                  const otherToggle = document.querySelector(
                    `[data-category="${list.id.replace('subcategory-', '')}"]`);
                  if (otherToggle) {
                    otherToggle.setAttribute('aria-expanded', 'false');
                    const otherIcon = otherToggle.querySelector('i');
                    if (otherIcon) {
                      otherIcon.classList.remove('bi-dash');
                      otherIcon.classList.add('bi-plus');
                    }
                  }
                }
              });

              // Open this subcategory
              subcategoryList.classList.add('show');
              this.setAttribute('aria-expanded', 'true');
              this.querySelector('i').classList.remove('bi-plus');
              this.querySelector('i').classList.add('bi-dash');
            }
          }
        });
      });

      // Close subcategory lists when dropdown is closed
      const navbarDropdown = document.getElementById('navbarDropdown');
      if (navbarDropdown) {
        // Using Bootstrap's event
        const dropdownMenu = navbarDropdown.closest('.dropdown');
        if (dropdownMenu) {
          dropdownMenu.addEventListener('hidden.bs.dropdown', function() {
            resetCategoryDropdowns();
          });
        }

        // Fallback for when clicking outside
        document.addEventListener('click', function(e) {
          if (!e.target.closest('.dropdown-menu-language') && !e.target.closest('#navbarDropdown')) {
            resetCategoryDropdowns();
          }
        });
      }

      // Helper function to reset all category dropdowns
      function resetCategoryDropdowns() {
        document.querySelectorAll('.subcategory-list').forEach(list => {
          list.classList.remove('show');
        });

        document.querySelectorAll('.category-toggle').forEach(toggle => {
          toggle.setAttribute('aria-expanded', 'false');
          const icon = toggle.querySelector('i');
          if (icon) {
            icon.classList.remove('bi-dash');
            icon.classList.add('bi-plus');
          }
        });
      }

      // Update current time
      function updateTime() {
        const timeElement = document.querySelector('.dropdown-item-text .text-muted span:last-child');
        if (timeElement) {
          const now = new Date();
          const hours = String(now.getHours()).padStart(2, '0');
          const minutes = String(now.getMinutes()).padStart(2, '0');
          timeElement.textContent = `${hours}:${minutes}`;
        }
      }

      // Update time every minute
      setInterval(updateTime, 60000);
    });
  </script>
@endpush
