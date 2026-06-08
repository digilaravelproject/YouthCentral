<!-- Navbar -->
 <nav class="navbar navbar-main navbar-expand-lg pxmx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
            <li class="breadcrumb-item text-sm text-dark active text-capitalize" aria-current="page">{{ str_replace('-', ' ', Request::path()) }}</li>
            </ol>
        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar"> 
            <div class="ms-md-3 pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav justify-content-end" style="width: 100%;">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
                </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
                </a>
                <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton">
                @php $student = Auth::guard('student')->user(); @endphp
                @foreach($student->notifications()->orderBy('created_at', 'desc')->limit(6)->get() as $notification)
                    @php $data = (array) $notification->data; @endphp
                    <li class="mb-2">
                        <div class="dropdown-item border-radius-md d-flex justify-content-between align-items-start">
                            <div class="d-flex py-1">
                                <div class="my-auto">
                                    <div class="avatar avatar-sm bg-gradient-info me-3">
                                        <i class="ni ni-bell-55 text-white"></i>
                                    </div>
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                    <h6 class="text-sm font-weight-normal mb-1">
                                        <span class="font-weight-bold">{{ $data['title'] ?? 'Notification' }}</span>
                                    </h6>
                                    <p class="text-xs text-secondary mb-0">
                                        {{ $data['message'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-secondary mb-0 mt-1">
                                        <i class="fa fa-clock me-1"></i>
                                        {{ optional($notification->created_at)->format('d M h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="ms-2">
                                @if(is_null($notification->read_at))
                                    <form action="{{ route('student.notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-link">Mark read</button>
                                    </form>
                                @else
                                    <form action="{{ route('student.notifications.unread', $notification->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-link">Mark unread</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
                @if($student->notifications()->count() == 0)
                    <li class="mb-2 text-center text-secondary">No notifications</li>
                @endif
                </ul>
            </li>
            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
