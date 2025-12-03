@extends('layouts.user_type.auth')

@section('content')

<main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg ">
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0 d-flex justify-content-between align-items-center">
            <h6>Category List</h6>

            <a href="{{ route('categories.create') }}" class="btn btn-primary btn-sm">
              + Add Category
            </a>
          </div>
          <div class="card-body px-0 pt-0 pb-2">
            <div class="table-responsive p-0">
              <table class="table align-items-center mb-0">
                <thead>
                  <tr>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7">Category Name</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Type</th>
                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">Actions</th>
                  </tr>
                </thead>

                <tbody>

                  {{-- Loop Data --}}
                  @forelse($categories as $category)
                  <tr>

                    <td>
                      <p class="text-xs font-weight-bold mb-0 text-center">{{ $category->name }}</p>
                    </td>

                    <td class="align-middle text-center text-sm">
                      @if($category->type == 'income')
                      <span class="badge bg-gradient-success ">Income</span>
                      @else
                      <span class="badge bg-gradient-danger">Expense</span>
                      @endif
                    </td>
                    <td class="text-center">
                      <a href="{{ route('categories.edit', $user->id) }}"
                        class="mx-3"
                        data-bs-toggle="tooltip"
                        data-bs-original-title="Edit user">
                        <i class="fas fa-user-edit text-secondary"></i>
                      </a>

                      <form action="{{ route('categories.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                          class="btn btn-link p-0 m-0"
                          onclick="return confirm('Delete this user?')">
                          <i class="cursor-pointer fas fa-trash text-secondary"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="4" class="text-center py-3 text-secondary">No categories found.</td>
                  </tr>
                  @endforelse

                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</main>

@endsection