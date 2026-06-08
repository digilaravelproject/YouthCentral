@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Bulk Import/Export</h6>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Success!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Warning!</strong> {{ session('warning') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('validation_errors'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Import Validation Errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach(session('validation_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('show_errors') && session('import_errors'))
                        <div class="alert alert-info alert-dismissible fade show" role="alert">
                            <strong>Import Details:</strong>
                            @if(session('import_stats'))
                                @php $stats = session('import_stats'); @endphp
                                <div class="mt-2">
                                    <span class="badge bg-success">Success: {{ $stats['success_count'] }}</span>
                                    <span class="badge bg-warning">Skipped: {{ $stats['skip_count'] }}</span>
                                    <span class="badge bg-danger">Errors: {{ $stats['error_count'] }}</span>
                                </div>
                            @endif
                            
                            @if(count(session('import_errors')) > 0)
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#errorDetails" aria-expanded="false">
                                        Show Error Details
                                    </button>
                                    <div class="collapse mt-2" id="errorDetails">
                                        <div class="card card-body">
                                            <ul class="mb-0">
                                                @foreach(session('import_errors') as $error)
                                                    <li><small>{{ $error }}</small></li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Import Section -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Import Businesses</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <strong>Import Requirements:</strong>
                                        <ul class="mb-0 mt-2">
                                            <li>Only <strong>business_name</strong> is required</li>
                                            <li>All other fields are optional and can be empty</li>
                                            <li>Empty categories/locations will be set to "General/Unknown"</li>
                                            <li>Duplicate businesses (same name + area) will be updated</li>
                                        </ul>
                                    </div>
                                    
                                    <form action="{{ route('admin.bulk-import') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group">
                                            <label for="file">Choose Excel/CSV File</label>
                                            <input type="file" class="form-control" id="file" name="file" accept=".csv,.xlsx,.xls" required>
                                            <small class="form-text text-muted">
                                                Accepted formats: .csv, .xlsx, .xls (max 10MB)
                                            </small>
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-upload me-1"></i> Import
                                            </button>
                                            <a href="{{ route('admin.bulk-sample-download') }}" class="btn btn-info">
                                                <i class="fas fa-download me-1"></i> Download Sample
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Export Section -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Export Businesses</h6>
                                </div>
                                <div class="card-body">
                                    <p>Export all businesses with their categories and locations.</p>
                                    <a href="{{ route('admin.bulk-export') }}" class="btn btn-success">
                                        <i class="fas fa-file-excel me-1"></i> Export to Excel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- CSV Structure -->
                    <div class="mt-4">
                        <h6>CSV/Excel Structure</h6>
                        <div class="alert alert-warning">
                            <strong>Note:</strong> Only <strong>business_name</strong> is required. All other fields are optional and will be handled gracefully if empty.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Column</th>
                                        <th>Required</th>
                                        <th>Description</th>
                                        <th>Default if Empty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-danger">
                                        <td><strong>business_name</strong></td>
                                        <td><span class="badge bg-danger">Required</span></td>
                                        <td>Name of the business</td>
                                        <td>-</td>
                                    </tr>
                                    <tr>
                                        <td>category</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Main category name</td>
                                        <td>"General"</td>
                                    </tr>
                                    <tr>
                                        <td>subcategory</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Subcategory name</td>
                                        <td>"General"</td>
                                    </tr>
                                    <tr>
                                        <td>state</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>State name</td>
                                        <td>"Unknown"</td>
                                    </tr>
                                    <tr>
                                        <td>city</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>City name</td>
                                        <td>"Unknown"</td>
                                    </tr>
                                    <tr>
                                        <td>area</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Area name</td>
                                        <td>"Unknown"</td>
                                    </tr>
                                    <tr>
                                        <td>phone</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Business phone number</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>whatsapp_number</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>WhatsApp number</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>email</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Business email address</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>website</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Business website URL (accepts any format: with/without https, www, etc.)</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>street_address</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Street address</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>description</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Business description</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>latitude</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Latitude coordinate (-90 to 90)</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>longitude</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Longitude coordinate (-180 to 180)</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>zipcode</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Postal/ZIP code</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>facebook_link</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Facebook page URL (flexible format)</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>instagram_link</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Instagram profile URL (flexible format)</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>twitter_link</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Twitter profile URL (flexible format)</td>
                                        <td>null</td>
                                    </tr>
                                    <tr>
                                        <td>pinterest_link</td>
                                        <td><span class="badge bg-success">Optional</span></td>
                                        <td>Pinterest profile URL (flexible format)</td>
                                        <td>null</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 