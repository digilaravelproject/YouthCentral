<!-- Navbar -->
 <nav class="navbar navbar-main navbar-expand-lg pxmx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page"><?php echo e(str_replace('-', ' ', Request::path())); ?></li>
            </ol>
            
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar"> 
            <?php /*<div class="nav-item d-flex align-self-end" style="width: 100%;">
                @if(Auth::check() && Auth::user()->role == 'admin')
                    <a href="{{ route('admin.businesses.create') }}" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                        <i class="fas fa-plus-circle me-2"></i>Add New Business
                    </a>
                @elseif(Auth::check() && Auth::user()->role == 'vendor')
                    <a href="{{ route('vendor.businesses.create') }}" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                        <i class="fas fa-plus-circle me-2"></i>Add New Listing
                    </a>
                @else
                    <a href="{{ route('home') }}" class="btn btn-primary active mb-0 text-white" role="button" aria-pressed="true">
                        <i class="fas fa-home me-2"></i>Home
                    </a>
                @endif
            </div> */?>
            <div class="ms-md-3 pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav justify-content-end" style="width: 100%;">
            <li class="nav-item d-flex align-items-center">
                
                <a href="<?php echo e(route('profile')); ?>" class="nav-link text-body font-weight-bold px-0">
                    <i class="fa fa-user me-sm-1"></i>
                    <span class="d-sm-inline d-none">Profile</span>
                </a>
            </li>
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
                </a>
            </li>
            
            
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar --><?php /**PATH C:\xampp\htdocs\YCcenter\resources\views/layouts/navbars/auth/nav.blade.php ENDPATH**/ ?>