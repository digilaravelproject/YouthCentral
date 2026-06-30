@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    @if(session('success'))
        <div class="alert alert-success text-white mx-0 mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-white mx-0 mb-4">{{ session('error') }}</div>
    @endif

    <!-- Import/Sample XLSX Card -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-excel text-success me-3" style="font-size: 1.5rem;"></i>
                            <div>
                                <h6 class="mb-0 text-sm">Import Cities from Excel</h6>
                                <p class="text-xs text-muted mb-0">Upload an XLSX file to populate cities data</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-center flex-wrap gap-2">
                            <!-- Import Form -->
                            <form action="{{ route('admin.cities.import') }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center mb-0">
                                @csrf
                                <input type="file" name="file" accept=".xlsx,.xls" required class="form-control form-control-sm me-2" style="width: auto;">
                                <button type="submit" class="btn btn-success btn-sm mb-0">Import XLSX</button>
                            </form>
                            <!-- Download Sample Link -->
                            <a href="{{ route('admin.cities.sample') }}" class="btn btn-outline-primary btn-sm mb-0">Download Sample XLSX</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Cities</h6>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Bulk Delete Form -->
                            <form id="bulk-delete-form" action="{{ route('admin.cities.bulk-delete') }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete the selected cities?')">
                                @csrf
                                <input type="hidden" name="ids" id="bulk-delete-ids">
                                <button type="submit" id="bulk-delete-btn" class="btn btn-danger btn-sm mb-0" disabled>Delete Selected</button>
                            </form>
                            
                            <a href="{{ route('admin.cities.create') }}" class="btn btn-primary btn-sm mb-0">Add New</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7" style="width: 40px; padding-left: 24px;">
                                        <input type="checkbox" id="select-all">
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">State</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Areas</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cities as $city)
                                <tr>
                                    <td class="align-middle" style="padding-left: 24px;">
                                        <input type="checkbox" class="select-item" value="{{ $city->id }}">
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">{{ $city->id }}</span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $city->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $city->state->name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $city->areas_count }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('admin.cities.edit', $city) }}" class="text-secondary font-weight-bold text-xs me-3" data-toggle="tooltip" data-original-title="Edit city">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.cities.destroy', $city) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this city?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Infinite scroll indicators will be added by JavaScript -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/admin-infinite-scroll.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateBulkDeleteState() {
            const selectItems = document.querySelectorAll('.select-item');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const bulkDeleteIds = document.getElementById('bulk-delete-ids');
            
            const checkedIds = Array.from(selectItems)
                .filter(item => item.checked)
                .map(item => item.value);
            
            if (bulkDeleteIds) {
                bulkDeleteIds.value = JSON.stringify(checkedIds);
            }
            
            if (bulkDeleteBtn) {
                if (checkedIds.length > 0) {
                    bulkDeleteBtn.removeAttribute('disabled');
                } else {
                    bulkDeleteBtn.setAttribute('disabled', 'disabled');
                }
            }
        }

        document.addEventListener('change', function(e) {
            if (e.target && e.target.id === 'select-all') {
                const selectItems = document.querySelectorAll('.select-item');
                selectItems.forEach(item => {
                    item.checked = e.target.checked;
                });
                updateBulkDeleteState();
            }
            
            if (e.target && e.target.classList.contains('select-item')) {
                const selectAll = document.getElementById('select-all');
                const selectItems = document.querySelectorAll('.select-item');
                if (!e.target.checked && selectAll) {
                    selectAll.checked = false;
                } else if (selectAll && Array.from(selectItems).every(i => i.checked)) {
                    selectAll.checked = true;
                }
                updateBulkDeleteState();
            }
        });
    });
</script>
@endpush 