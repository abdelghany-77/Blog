<tr>
  <td>
    <div class="d-flex align-items-center">
      @if ($level > 0)
        <div style="width: {{ $level * 24 }}px"></div>
      @endif

      @if ($category->hasChildren())
        <i class="bi bi-folder-fill text-warning me-2"></i>
      @else
        <i class="bi bi-tag-fill text-info me-2"></i>
      @endif

      <span class="fw-medium">{{ $category->name }}</span>
    </div>
  </td>
  <td>
    <span class="text-muted">{{ Str::limit($category->description, 50) }}</span>
  </td>
  <td>
    <span class="badge bg-primary rounded-pill">{{ $category->posts->count() }}</span>
  </td>
  <td>
    @if ($category->is_active)
      <span class="badge bg-success">Active</span>
    @else
      <span class="badge bg-secondary">Inactive</span>
    @endif
  </td>
  <td class="text-end">
    <div class="btn-group">
      <a href="{{ route('admin.category.edit', $category) }}" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-pencil"></i>
      </a>
      <form action="{{ route('admin.category.destroy', $category) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-outline-danger"
          onclick="return confirm('Are you sure you want to delete this category?')">
          <i class="bi bi-trash"></i>
        </button>
      </form>
    </div>
  </td>
</tr>

{{-- Render children recursively --}}
@if ($category->children)
  @foreach ($category->children as $child)
    @include('admin.category.partials.category-row', ['category' => $child, 'level' => $level + 1])
  @endforeach
@endif
