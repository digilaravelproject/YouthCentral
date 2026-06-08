@extends('layouts.app-public')

@section('title', $event->title . ' - Event Details')

@section('content')
    <!-- Start Search Popup -->
    <div class="search-popup container-fluid hero-header">
        <!-- Start Header Centralizer -->
        <div class="header-centralizer">
            <div class="content-centralized">
                <!-- Start Hero Search -->

                <div class="hero-search">
                    <form action="{{ route('search') }}" method="GET">
                        <fieldset>
                            <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy...">
                            <span class="typingEffect"
                                data-title="Explore Opportunities//Explore Your Passion//Explore Yourself//Do Memorable Check-ins//Go Beyond and Beyond"></span>
                        </fieldset>
                        <!-- Start Search Cities -->
                        <div class="search-cities-toggle"></div>
                        <div class="search-cities">
                            <div class="cities-list">
                                @foreach ($popularCities as $index => $city)
                                    <a href="#"
                                        style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')"
                                        data-city-id="{{ $city->id }}"
                                        {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span></a>
                                @endforeach
                                <a href="#"
                                    style="background-image:url('{{ asset('assets_public/images/cities/thumbs/5.jpg') }}')"
                                    data-city-id="more" class="go-more-cities"><span>More Cities</span></a>
                                <input class="chosen-city" type="hidden" name="city" value="0">
                            </div>
                        </div>
                        <!-- End Search Cities -->
                        <div class="search-submit">
                            <input type="submit" value=" ">
                            <i class="hero-search-icon"></i>
                        </div>
                    </form>
                </div>
                <h3 style="color: #fff; text-align: left; font-size: 20px; margin-left: 10%;">Dream Bigger, Start Here</h3>
                <!-- End Hero Search -->
                <!-- Start Search Categories -->
                <div class="search-categories">
                    <div class="categories">
                        @php
                            // Map custom categories to real subcategories with their icons and routes
                            $customCategoryMappings = [
                                'Tuitions' => [
                                    'icon' => 'fas fa-book-open',
                                    'search_terms' => ['tuition', 'tutoring', 'tutor'],
                                ],
                                'Football/Soccer' => [
                                    'icon' => 'fas fa-futbol',
                                    'search_terms' => ['football', 'soccer'],
                                ],
                                'Cricket' => ['icon' => 'fas fa-baseball-ball', 'search_terms' => ['cricket']],
                                'Swimming' => ['icon' => 'fas fa-swimmer', 'search_terms' => ['swimming', 'swim']],
                                'Coaching Classes' => [
                                    'icon' => 'fas fa-chalkboard-teacher',
                                    'search_terms' => ['coaching', 'classes', 'training'],
                                ],
                                'Computers/AI' => [
                                    'icon' => 'fas fa-laptop-code',
                                    'search_terms' => ['computer', 'ai', 'programming', 'coding'],
                                ],
                                'Theatre/Acting' => [
                                    'icon' => 'fas fa-theater-masks',
                                    'search_terms' => ['theatre', 'acting', 'drama'],
                                ],
                                'Music' => ['icon' => 'fas fa-music', 'search_terms' => ['music', 'musical']],
                                'Day Care' => [
                                    'icon' => 'fas fa-baby',
                                    'search_terms' => ['daycare', 'day care', 'childcare'],
                                ],
                                'Chess' => ['icon' => 'fas fa-chess', 'search_terms' => ['chess']],
                                'Table Tennis' => [
                                    'icon' => 'fas fa-table-tennis',
                                    'search_terms' => ['table tennis', 'ping pong'],
                                ],
                                'Martial Arts/Karate' => [
                                    'icon' => 'fas fa-fist-raised',
                                    'search_terms' => ['martial arts', 'karate', 'taekwondo'],
                                ],
                                'Foundational Stem' => [
                                    'icon' => 'fas fa-atom',
                                    'search_terms' => ['stem', 'science', 'foundational'],
                                ],
                                'Maths/Science' => [
                                    'icon' => 'fas fa-calculator',
                                    'search_terms' => ['math', 'science', 'mathematics'],
                                ],
                                'Library' => ['icon' => 'fas fa-book', 'search_terms' => ['library', 'libraries']],
                                'Pediatrician' => [
                                    'icon' => 'fas fa-user-md',
                                    'search_terms' => ['pediatrician', 'pediatric'],
                                ],
                                'Counselling' => [
                                    'icon' => 'fas fa-comments',
                                    'search_terms' => ['counselling', 'counseling', 'therapy'],
                                ],
                                'Painting/Sketching' => [
                                    'icon' => 'fas fa-palette',
                                    'search_terms' => ['painting', 'sketching', 'art'],
                                ],
                            ];

                            // Find matching subcategories for each custom category
                            $customCategories = [];
                            foreach ($customCategoryMappings as $categoryName => $mapping) {
                                // Try to find a matching subcategory
                                $subcategory = null;
                                foreach ($mapping['search_terms'] as $term) {
                                    $subcategory = \App\Models\Subcategory::where(
                                        'name',
                                        'LIKE',
                                        '%' . $term . '%',
                                    )->first();
                                    if ($subcategory) {
                                        break;
                                    }
                                }

                                if ($subcategory) {
                                    $customCategories[] = [
                                        'name' => $categoryName,
                                        'icon' => $subcategory->icon_class
                                            ? $subcategory->getFormattedIconClass()
                                            : $mapping['icon'],
                                        'route' => route('listings', $subcategory),
                                    ];
                                } else {
                                    // Fallback to search route
                                    $customCategories[] = [
                                        'name' => $categoryName,
                                        'icon' => $mapping['icon'],
                                        'route' => route('search') . '?query=' . urlencode($categoryName),
                                    ];
                                }
                            }
                        @endphp
                        @foreach ($customCategories as $category)
                            <a class="child" href="{{ $category['route'] }}">
                                <i class="{{ $category['icon'] }}"></i>
                                <span>{{ $category['name'] }}</span>
                            </a>
                        @endforeach
                        <a href="{{ route('directory.index') }}">
                            <i class="fas fa-city"></i>
                            <span>More Options</span>
                        </a>
                    </div>
                </div>
                <!-- End Search Categories -->
            </div>
        </div>
    </div>
    <!-- End Search Popup -->
    <!-- Hero Image Section -->
    {{-- @php
        $heroImage = '';
        if ($event->banner_image) {
            $heroImage = asset('storage/' . $event->banner_image);
        } elseif ($event->banners && $event->banners->count() > 0) {
            $primaryBanner = $event->banners->where('is_primary', true)->first();
            $bannerImage = $primaryBanner ?: $event->banners->first();
            if ($bannerImage) {
                $heroImage = asset('storage/' . $bannerImage->image_path);
            }
        }
        if (!$heroImage) {
            $heroImage = asset('assets_public/images/backgrounds/8.jpg');
        }
    @endphp --}}
    <div class="event-hero-section">
        <div class="event-hero-overlay"></div>
        <div class="container">
            <div class="event-hero-content text-center">
                <h1 class="event-title" style="color: #fff !important;">{{ $event->title }}</h1>
                <div class="event-meta">
                    <span style="color: #fff !important;"><i class="fa-solid fa-calendar"></i> {{ $event->start_date->format('D, M d, Y') }}</span>
                    <span style="color: #fff !important;"><i class="fa-regular fa-clock"></i> {{ $event->start_date->format('h:i A') }} -
                        {{ $event->end_date->format('h:i A') }}</span>
                    <span style="color: #fff !important;"><i class="fa-solid fa-location-dot"></i> {{ $event->venue }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Details & Registration Section -->
    <div class="event-details-section container my-5">
        <div class="row">
            <!-- Left Column: Event Information -->
            <div class="col-lg-8" style="padding-bottom: 5%;">
                {{-- About Card --}}
                <div class="card event-info-card mb-4">
                    <div class="card-header">
                        <h4 class="card-title mb-0">About This Event</h4>
                    </div>
                    <div class="card-body">
                        {{-- Display Banner Image if available --}}
                        @if ($event->banner_image)
                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}"
                                class="img-fluid rounded mb-4 event-about-image">
                        @elseif($event->banners && $event->banners->count() > 0)
                            @php
                                $primaryBanner = $event->banners->where('is_primary', true)->first();
                                $bannerImage = $primaryBanner ?: $event->banners->first();
                            @endphp
                            @if ($bannerImage)
                                <img src="{{ asset('storage/' . $bannerImage->image_path) }}" alt="{{ $event->title }}"
                                    class="img-fluid rounded mb-4 event-about-image">
                            @endif
                        @endif
                        <div class="event-description">
                            {!! nl2br(e($event->description)) !!}
                        </div>

                        <a href="{{route('events.faqs')}}">For FAQ'S Click Here</a>
                    </div>
                </div>

                {{-- Schedule Card (Optional) --}}
                @if ($event->event_schedule)
                    <div class="card event-info-card mb-4">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Event Schedule</h4>
                        </div>
                        <div class="card-body">
                            <div class="event-schedule">
                                {!! nl2br(e($event->event_schedule)) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Terms Card (Optional) --}}
                @if ($event->terms_and_conditions)
                    <div class="card event-info-card mb-4">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Terms & Conditions</h4>
                        </div>
                        <div class="card-body">
                            <div class="terms-conditions">
                                {!! nl2br(e($event->terms_and_conditions)) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Registration Card (Sticky) -->
            <div class="col-lg-4">
                @if ($event->category === 'yc')
                    <div class="card registration-card sticky-top">
                        <div class="card-header text-center">
                            <h4 class="card-title mb-0">Register Now</h4>
                        </div>
                        <div class="card-body">
                            @auth
                                @php
                                    $alreadyRegistered = \App\Models\YcIgnite::where('event_id', $event->id)
                                        ->where('user_id', Auth::id())
                                        ->exists();
                                @endphp

                                @if ($alreadyRegistered)
                                    <div class="alert text-center">
                                        ✅ Registration already completed.
                                    </div>
                                @else
                                    @include('public.events.partials.yc_ignite-form', [
                                        'event' => $event,
                                        'user' => Auth::user(),
                                    ])
                                @endif
                            @else
                                <div class="alert alert-info text-center mb-3">
                                    Please <a href="{{ route('login') }}">login</a> to register.
                                </div>
                            @endauth
                        </div>
                    </div>
                @else
                    <div class="card event-info-card sticky-top">
                        <div class="card-header text-center">
                            <h4 class="card-title mb-0">Event Details</h4>
                        </div>
                        <div class="card-body">
                            <div class="event-details-sidebar">
                                <div class="event-detail-item mb-3">
                                    <i class="fa-solid fa-calendar"></i>
                                    <strong>Date:</strong>
                                    <p>{{ $event->start_date->format('D, M d, Y') }}</p>
                                </div>

                                <div class="event-detail-item mb-3">
                                    <i class="fa-regular fa-clock"></i>
                                    <strong>Time:</strong>
                                    <p>{{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}
                                    </p>
                                </div>

                                <div class="event-detail-item mb-3">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <strong>Location:</strong>
                                    <p>{{ $event->venue }}</p>
                                </div>

                                @if ($event->seat_limit)
                                    <div class="event-detail-item mb-3">
                                        <i class="fa-solid fa-users"></i>
                                        <strong>Capacity:</strong>
                                        <p>{{ $event->seat_limit }} seats</p>
                                    </div>
                                @endif

                                <div class="alert alert-info text-center mt-3">
                                    <i class="fa-solid fa-info-circle"></i> This is an informational event. No registration
                                    required.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Hero Section */
        .event-hero-section {
            padding: 120px 0;
            background-size: cover;
            background-position: center center;
            position: relative;
            color: white;
        }

        .event-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.92);
        }

        .event-hero-content {
            position: relative;
            z-index: 1;
        }

        .event-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.5);
        }

        .event-meta span {
            margin: 0 15px;
            font-size: 16px;
            opacity: 0.9;
        }

        .event-meta i {
            margin-right: 8px;
            color: #3399cc;
            /* Theme color */
        }

        /* Details Section */
        .event-details-section {
            padding-top: 40px;
        }

        .card.event-info-card,
        .card.registration-card {
            border: none;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            border-radius: 8px;
        }

        .card.event-info-card .card-header,
        .card.registration-card .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #eee;
            padding: 1rem 1.5rem;
        }

        .card.event-info-card .card-title,
        .card.registration-card .card-title {
            font-weight: 600;
            color: #333;
        }

        .card.event-info-card .card-body {
            padding: 1.5rem;
            line-height: 1.7;
        }

        .event-description p,
        .event-schedule p,
        .terms-conditions p {
            margin-bottom: 1rem;
        }

        .event-description,
        .event-schedule,
        .terms-conditions {
            color: #555;
        }

        /* Registration Card */
        .registration-card .card-body {
            padding: 1.5rem;
        }

        .registration-card .sticky-top {
            top: 20px;
            /* Adjust sticky top position */
        }

        .price-tag {
            text-align: center;
            margin-bottom: 1.5rem;
            /* Increased margin */
        }

        .price-tag .amount {
            font-size: 2.5rem;
            /* Larger amount */
            font-weight: 700;
            color: var(--primary-color);
            /* Theme color */
            display: block;
        }

        .price-tag .per-person {
            display: block;
            color: #666;
            font-size: 0.9rem;
            margin-top: -5px;
        }

        .registration-card hr {
            border-top: 1px solid #eee;
        }

        .registration-card .form-group {
            margin-bottom: 1rem;
            /* Consistent spacing */
        }

        .registration-card .btn-primary {
            padding: 0.75rem 1.25rem;
            /* Larger button */
            font-size: 1rem;
            font-weight: 600;
            background-color: var(--primary-color);
            /* Theme color */
            border-color: var(--primary-color);
        }

        .registration-card .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .registration-card .alert {
            font-size: 0.9rem;
        }

        /* Event Details Sidebar */
        .event-details-sidebar {
            padding: 0.5rem;
        }

        .event-detail-item {
            padding: 10px;
            border-bottom: 1px solid #eee;
        }

        .event-detail-item i {
            color: #3399cc;
            margin-right: 8px;
        }

        .event-detail-item strong {
            display: inline-block;
            margin-bottom: 5px;
        }

        .event-detail-item p {
            margin-bottom: 0;
            color: #555;
            margin-left: 18px;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropdown = document.getElementById("age_category");
            const priceElement = document.getElementById("schoolTypePrice");

            function updatePrice() {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                const price = selectedOption.getAttribute("data-price");

                if (price) {
                    priceElement.textContent = "₹" + new Intl.NumberFormat().format(price);
                } else {
                    priceElement.textContent = "--";
                }
            }

            if (dropdown) {
                // update price on change
                dropdown.addEventListener("change", updatePrice);

                // trigger once on load for default selection
                updatePrice();
            }
        });
    </script>




    {{-- Categories are now handled directly in the HTML above, no JavaScript needed --}}
@endsection
