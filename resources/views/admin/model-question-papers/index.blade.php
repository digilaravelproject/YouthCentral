@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Model Question Papers</h6>
                        <a href="{{ route('admin.model-question-papers.create') }}" class="btn btn-primary btn-sm">Add Model Question Paper</a>
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
                                    <th>Title</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                    <th>Topic</th>
                                    <th>Month</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($papers as $paper)
                                    <tr>
                                        <td>{{ $paper->title }}</td>
                                        <td><span class="badge badge-sm bg-gradient-info">{{ $paper->type }}</span></td>
                                        <td>{{ $paper->subject ?? '-' }}</td>
                                        <td>{{ $paper->topic ?? '-' }}</td>
                                        <td>{{ $paper->month ? \Carbon\Carbon::parse($paper->month)->format('M Y') : '-' }}</td>
                                        <td>
                                            <span class="badge bg-gradient-{{ $paper->status ? 'success' : 'secondary' }}">{{ $paper->status ? 'Active' : 'Inactive' }}</span>
                                        </td>
                                        <td>{{ $paper->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.model-question-papers.show', $paper->id) }}" class="text-info">View</a>
                                            <a href="{{ route('admin.model-question-papers.edit', $paper->id) }}" class="text-primary ms-2">Edit</a>
                                            <form action="{{ route('admin.model-question-papers.destroy', $paper->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-danger border-0 bg-transparent" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center">No model question papers found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-4">{{ $papers->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
