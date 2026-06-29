@extends('layouts.app')

@section('auth')


    @if(\Request::is('static-sign-up')) 
        @include('layouts.navbars.guest.nav')
        @yield('content')
        @include('layouts.footers.guest.footer')
    
    @elseif (\Request::is('static-sign-in')) 
        @include('layouts.navbars.guest.nav')
            @yield('content')
        @include('layouts.footers.guest.footer')
    
    @else
        @if (\Request::is('rtl'))  
            @include('layouts.navbars.auth.sidebar-rtl')
            <main class="main-content position-relative mt-1 border-radius-lg">
                @include('layouts.navbars.auth.nav-rtl')
                <div class="container-fluid py-4">
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>

        @elseif (\Request::is('profile'))  
            @include('layouts.navbars.auth.sidebar')
            <div class="main-content position-relative bg-gray-100">
                @include('layouts.navbars.auth.nav')
                @yield('content')
            </div>

        @elseif (\Request::is('virtual-reality')) 
            @include('layouts.navbars.auth.nav')
            <div class="border-radius-xl mt-3 mx-3 position-relative" style="background-image: url('../assets/img/vr-bg.jpg') ; background-size: cover;">
                @include('layouts.navbars.auth.sidebar')
                <main class="main-content mt-1 border-radius-lg">
                    @yield('content')
                </main>
            </div>
            @include('layouts.footers.auth.footer')

        @else
            @if(Auth::guard('student')->check())
                @include('layouts.navbars.auth.student-sidebar')
            @else
                @include('layouts.navbars.auth.sidebar')
            @endif
            <main class="main-content position-relative mt-1 border-radius-lg">
                @if(Auth::guard('student')->check())
                    @include('layouts.navbars.auth.student-nav')
                @else
                    @include('layouts.navbars.auth.nav')
                @endif
                <div class="container-fluid py-4">
                    @if(Auth::check() && Auth::user()->role === 'vendor' && !Auth::user()->hasActiveSubscription() && !Request::is('vendor/subscriptions/plans') && !Request::is('vendor/subscriptions/current*'))
                        <style>
                            @keyframes alertFadeIn {
                                from {
                                    opacity: 0;
                                    transform: translateY(-12px);
                                }
                                to {
                                    opacity: 1;
                                    transform: translateY(0);
                                }
                            }
                            .premium-warning-banner {
                                border-radius: 12px;
                                box-shadow: 0 4px 15px rgba(245, 57, 57, 0.15);
                                animation: alertFadeIn 0.45s cubic-bezier(0.16, 1, 0.3, 1);
                                transition: transform 0.2s ease, box-shadow 0.2s ease;
                            }
                            .premium-warning-banner:hover {
                                transform: translateY(-2px);
                                box-shadow: 0 6px 20px rgba(245, 57, 57, 0.22);
                            }
                        </style>
                        <div class="alert bg-gradient-warning text-white font-weight-bold d-flex align-items-center p-3 mb-4 border-0 premium-warning-banner" role="alert" style="font-size: 1.1rem; justify-content: center;">
                            <i class="fas fa-exclamation-triangle me-3 text-white" style="font-size: 1.4rem;"></i>
                            <span>Subscription required for explore your business</span>
                        </div>
                    @endif
                    @yield('content')
                    @include('layouts.footers.auth.footer')
                </div>
            </main>
        @endif

        {{-- @include('components.fixed-plugin') --}}
    @endif

    

@endsection