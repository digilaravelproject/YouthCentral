@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Pending Vendor Approvals</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success text-white mx-4">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger text-white mx-4">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    @if($pendingVendors->isEmpty())
                        <div class="p-4 text-center">
                            <p>No pending vendor approvals at this time.</p>
                        </div>
                    @else
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Vendor</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Business Details</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Registered On</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingVendors as $vendor)
                                <tr>
                                    <td>
                                        <div class="d-flex px-3 py-1">
                                            <div>
                                                <i class="fa fa-user text-secondary"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center ms-3">
                                                <h6 class="mb-0 text-sm">{{ $vendor->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $vendor->email }}</p>
                                                <p class="text-xs text-secondary mb-0">{{ $vendor->phone }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $vendor->business_name }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $vendor->business_address }}</p>
                                        @if($vendor->gst_number)
                                            <p class="text-xs text-secondary mb-0">GST: {{ $vendor->gst_number }}</p>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $vendor->created_at->format('M d, Y') }}</p>
                                        <p class="text-xs text-secondary mb-0">{{ $vendor->created_at->format('h:i A') }}</p>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex">
                                            <form action="{{ route('admin.vendors.approve', $vendor->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm bg-gradient-success me-2">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.vendors.reject', $vendor->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm bg-gradient-danger">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 