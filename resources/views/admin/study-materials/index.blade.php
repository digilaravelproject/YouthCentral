@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Study Materials</h6>
                        <a href="{{ route('admin.study-materials.create') }}" class="btn btn-primary btn-sm">Add Study Material</a>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success mx-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Title</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Subject</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Topic</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Month</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materials as $material)
                                    <tr>
                                        <td>
                                            <div class="d-flex px-2 py-1">
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-sm">{{ $material->title }}</h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-sm bg-gradient-info">{{ $material->type }}</span>
                                        </td>
                                        <td>
                                            {{ $material->subject ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $material->topic ?? '-' }}
                                        </td>
                                        <td>
                                            {{ $material->month ? \Carbon\Carbon::parse($material->month)->format('M Y') : '-' }}
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.study-materials.toggle-status', $material->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="badge badge-sm border-0 bg-gradient-{{ $material->status ? 'success' : 'secondary' }}">
                                                    {{ $material->status ? 'Active' : 'Inactive' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td>
                                            <p class="text-xs font-weight-bold mb-0">{{ $material->created_at->format('M d, Y') }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <a href="{{ route('admin.study-materials.show', $material->id) }}" class="text-info font-weight-bold text-xs me-3">View</a>
                                            <a href="{{ route('admin.study-materials.edit', $material->id) }}" class="text-primary font-weight-bold text-xs me-3">Edit</a>
                                            <form action="{{ route('admin.study-materials.destroy', $material->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">No study materials found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4">
                        {{ $materials->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
