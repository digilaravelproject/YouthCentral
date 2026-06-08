@extends('layouts.app-public')
@section('title', $event->title . ' - Event Details')
@section('content')
    <style>
        @media (max-width: 576px) {
            ._ad_row {
                display: flex;
                flex-direction: column-reverse;
            }
        }
        
        /* Hero Section - Background Image */
        .event-hero-section {
            /* padding: 120px 0; */
            background: rgba(0, 0, 0, 0.6) url('{{ asset('assets_public/images/background.jpg') }}') center/cover no-repeat;
            position: relative;
            min-height: 400px;
            display: flex;
            align-items: center;
            margin-top: 120px;
            z-index: 1;
        }
        
        .event-hero-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            z-index: 0;
        }
        
        .event-hero-content {
            position: relative;
            z-index: 10;
            text-align: center;
        }
        
        .event-hero-section_gen {
            padding: 120px 0;
            background-size: cover;
            background-position: center center;
            background-repeat: no-repeat;
            position: relative;
            color: white;
            min-height: 400px;
            display: flex;
            align-items: center;
            margin-top: 60px;
            z-index: 1;
        }
        
        .event-hero-overlay_gen {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.92);
            z-index: 0;
        }

        .event-hero-content_gen {
            position: relative;
            z-index: 10;
        }
        
        .event-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.7);
        }
        .event-meta span {
            margin: 0 15px;
            font-size: 1.1rem;
            opacity: 0.95;
        }
        .event-meta i {
            margin-right: 8px;
            color: #3399cc;
        }
        /* Details Section */
        .event-details-section {
            padding-top: 40px;
        }
        .card.event-info-card,
        .card.registration-card {
            border: none;
            box-shadow: 0 2px 15px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
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
            margin: 0;
        }
        .card.event-info-card .card-body {
            padding: 1.5rem;
            line-height: 1.7;
            color: #555;
        }
        .event-about-image {
            max-height: 400px;
            object-fit: cover;
            width: 100%;
            border-radius: 6px;
        }
        /* Registration Card */
        .registration-card {
            top: 20px;
        }
        .price-tag {
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .price-tag .amount {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-color);
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
        .registration-card .btn-primary {
            padding: 0.75rem 1.25rem;
            font-size: 1rem;
            font-weight: 600;
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            border-radius: 6px;
        }
        .registration-card .btn-primary:hover {
            background-color: #0a0f1f;
            border-color: #0a0f1f;
        }
        /* Event Details Sidebar */
        .event-details-sidebar .event-detail-item {
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }
        .event-details-sidebar .event-detail-item:last-child {
            border-bottom: none;
        }
        .event-details-sidebar .event-detail-item i {
            color: #3399cc;
            margin-right: 8px;
            width: 20px;
            text-align: center;
        }
        .event-details-sidebar .event-detail-item strong {
            display: block;
            margin-bottom: 4px;
            color: #333;
        }
        .event-details-sidebar .event-detail-item p {
            margin: 0;
            color: #555;
            margin-left: 28px;
        }

        .alert {
            position: relative;
            z-index: 1050;
        }

        .alert {
            margin-top: 15px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        /* Result card styling */
        .result-card {
            background: transparent;
            border: 1px solid rgba(0,0,0,0.12);
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .result-card .card-body {
            padding: 18px 22px;
            text-align: center;
        }

        .result-card .result-link {
            display: flex;
            width: 100%;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #333;
            font-size: 1.4rem;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .result-card .result-link:hover .result-title {
            color: var(--primary-color, #3399cc);
        }

        .result-card .result-title {
            border-bottom: 1px dashed #bbb;
            transition: color 0.2s ease;
        }

        .result-card .result-label {
            color: var(--primary-color, #3399cc);
        }

        .result-card .click-here {
            margin-left: 8px;
            font-size: 0.8rem;
            display: inline-flex;
            align-items: center;
            position: relative;
            overflow: hidden;
            padding: 0 4px;
            border-radius: 4px;
            background: linear-gradient(90deg, #ff3cac, #784ba0, #2b86c5, #0fd850, #ffe600, #ff3cac);
            background-size: 500% 500%;
            color: transparent;
            -webkit-background-clip: text;
            background-clip: text;
            animation: rainbow-sprinkle 2.5s linear infinite;
            transition: transform 0.2s ease;
        }

        .result-card .result-link:hover .click-here {
            transform: translateX(3px);
        }

        @keyframes rainbow-sprinkle {
            0% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
            100% {
                background-position: 0% 50%;
            }
        }

        .result-card .result-icon {
            margin-left: 8px;
            color: #888;
            font-size: 0.9rem;
            transition: transform 0.2s ease, color 0.2s ease;
        }

        .result-card .result-link:hover .result-icon {
            transform: translateX(4px);
            color: var(--primary-color, #3399cc);
        }

    </style>

    <!-- Start Search Popup -->
    <div class="search-popup container-fluid hero-header">
        <div class="header-centralizer">
            <div class="content-centralized">
                <div class="hero-search">
                    <form action="{{ route('search') }}" method="GET">
                        <fieldset>
                            <input type="text" name="query" class="form-control" data-placeholder="Explore and Enjoy...">
                            <span class="typingEffect" data-title="Explore Opportunities//Explore Your Passion//Explore Yourself//Do Memorable Check-ins//Go Beyond and Beyond"></span>
                        </fieldset>
                        <div class="search-cities-toggle"></div>
                        <div class="search-cities">
                            <div class="cities-list">
                                @foreach($popularCities as $index => $city)
                                    <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}>
                                        <span>{{ $city->name }}</span>
                                    </a>
                                @endforeach
                                <a href="#" style="background-image:url('{{ asset('assets_public/images/cities/thumbs/5.jpg') }}')" data-city-id="more" class="go-more-cities">
                                    <span>More Cities</span>
                                </a>
                                <input class="chosen-city" type="hidden" name="city" value="0">
                            </div>
                        </div>
                        <div class="search-submit">
                            <input type="submit" value=" ">
                            <i class="hero-search-icon"></i>
                        </div>
                    </form>
                </div>
                <h3 style="color: #fff; text-align: left; font-size: 20px; margin-left: 10%;">Dream Bigger, Start Here</h3>

                <div class="search-categories">
                    <div class="categories">
                        @php
                            $customCategoryMappings = [
                                'Tuitions' => ['icon' => 'fas fa-book-open', 'search_terms' => ['tuition', 'tutoring', 'tutor']],
                                'Football/Soccer' => ['icon' => 'fas fa-futbol', 'search_terms' => ['football', 'soccer']],
                                'Cricket' => ['icon' => 'fas fa-baseball-ball', 'search_terms' => ['cricket']],
                                'Swimming' => ['icon' => 'fas fa-swimmer', 'search_terms' => ['swimming', 'swim']],
                                'Coaching Classes' => ['icon' => 'fas fa-chalkboard-teacher', 'search_terms' => ['coaching', 'classes', 'training']],
                                'Computers/AI' => ['icon' => 'fas fa-laptop-code', 'search_terms' => ['computer', 'ai', 'programming', 'coding']],
                                'Theatre/Acting' => ['icon' => 'fas fa-theater-masks', 'search_terms' => ['theatre', 'acting', 'drama']],
                                'Music' => ['icon' => 'fas fa-music', 'search_terms' => ['music', 'musical']],
                                'Day Care' => ['icon' => 'fas fa-baby', 'search_terms' => ['daycare', 'day care', 'childcare']],
                                'Chess' => ['icon' => 'fas fa-chess', 'search_terms' => ['chess']],
                                'Table Tennis' => ['icon' => 'fas fa-table-tennis', 'search_terms' => ['table tennis', 'ping pong']],
                                'Martial Arts/Karate' => ['icon' => 'fas fa-fist-raised', 'search_terms' => ['martial arts', 'karate', 'taekwondo']],
                                'Foundational Stem' => ['icon' => 'fas fa-atom', 'search_terms' => ['stem', 'science', 'foundational']],
                                'Maths/Science' => ['icon' => 'fas fa-calculator', 'search_terms' => ['math', 'science', 'mathematics']],
                                'Library' => ['icon' => 'fas fa-book', 'search_terms' => ['library', 'libraries']],
                                'Pediatrician' => ['icon' => 'fas fa-user-md', 'search_terms' => ['pediatrician', 'pediatric']],
                                'Counselling' => ['icon' => 'fas fa-comments', 'search_terms' => ['counselling', 'counseling', 'therapy']],
                                'Painting/Sketching' => ['icon' => 'fas fa-palette', 'search_terms' => ['painting', 'sketching', 'art']]
                            ];
                            $customCategories = [];
                            foreach ($customCategoryMappings as $categoryName => $mapping) {
                                $subcategory = null;
                                foreach ($mapping['search_terms'] as $term) {
                                    $subcategory = \App\Models\Subcategory::where('name', 'LIKE', '%' . $term . '%')->first();
                                    if ($subcategory) break;
                                }
                                if ($subcategory) {
                                    $customCategories[] = [
                                        'name' => $categoryName,
                                        'icon' => $subcategory->icon_class ? $subcategory->getFormattedIconClass() : $mapping['icon'],
                                        'route' => route('listings', $subcategory)
                                    ];
                                } else {
                                    $customCategories[] = [
                                        'name' => $categoryName,
                                        'icon' => $mapping['icon'],
                                        'route' => route('search') . '?query=' . urlencode($categoryName)
                                    ];
                                }
                            }
                        @endphp
                        @foreach($customCategories as $category)
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
            </div>
        </div>
    </div>
    <!-- End Search Popup -->
    
    @if($event->category == 'general')
        @if($event->featured_banner)
            <div class="event-hero-section_gen" style="background-image: url('{{ asset('storage/' . $event->featured_banner) }}');">
                <div class="event-hero-overlay_gen"></div>
                <div class="container">
                    <div class="event-hero-content_gen text-center">
                    </div>
                </div>
            </div>
        @else
            <div class="event-hero-section_gen">
                <div class="event-hero-overlay_gen"></div>
                <div class="container">
                    <div class="event-hero-content_gen text-center">
                    </div>
                </div>
            </div>
        @endif
    @else
        @if($event->featured_banner)
            <div class="event-hero-section" style="background-image: url('{{ asset('storage/' . $event->featured_banner) }}'); background-size: cover; background-position: center;">
                <div class="event-hero-overlay"></div>
                <div class="container">
                    <div class="event-hero-content">
                    </div>
                </div>
            </div>
        @else
            <!-- Hero Image Section -->
            <div class="event-hero-section">
                <div class="event-hero-overlay"></div>
                <div class="container">
                    <div class="event-hero-content">
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Event Details & Registration Section -->
    <div class="event-details-section container my-5">
        <div class="row _ad_row">
            <!-- Left Column: Event Information -->
            <div class="col-lg-8" style="padding-bottom: 10%;">
                {{-- ================= RESULT CARD ================= --}}
                <div class="card mb-4 result-card">
                    <div class="card-body">
                        <a href="{{ str_replace('2027', '2026', route('events.result', $event)) }}" target="_blank" class="result-link">
                            <span class="result-label">
                                Result:
                            </span>

                            <span class="result-title">
                                {{ str_replace('2027', '2026', $event->title) }}
                            </span>

                            <small class="click-here">
                                (Click Here)
                            </small>

                            <i class="fas fa-external-link-alt result-icon"></i>
                        </a>
                    </div>
                </div>
                {{-- ================= END RESULT CARD ================= --}}

                {{-- About Card --}}
                <div class="card event-info-card mb-4" style="margin-top: 4% !important;">
                    <div class="card-header">
                        <h4 class="card-title mb-0">About This Event</h4>
                    </div>
                    <div class="card-body">
                        {{-- Event name, date, time, location - displayed before description --}}
                        <h1 class="mb-3" style="font-size: 2rem; font-weight: 700; color: #333;">{{ $event->title }}</h1>
                        <div class="event-meta mb-4" style="display: flex; flex-wrap: wrap; gap: 30px; font-size: 1.1rem; color: #555;">
                            <span><i class="fa-solid fa-calendar" style="color: #3399cc; margin-right: 8px;"></i> {{ $event->start_date->format('D, M d, Y') }}</span>
                            <span><i class="fa-regular fa-clock" style="color: #3399cc; margin-right: 8px;"></i> {{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</span>
                            <span style="margin-bottom: 3% !important;"><i class="fa-solid fa-location-dot" style="color: #3399cc; margin-right: 8px;"></i> {{ $event->venue }}</span>
                        </div>
                        @if($event->banner_image)
                            <img src="{{ asset('storage/' . $event->banner_image) }}" alt="{{ $event->title }}" class="img-fluid rounded mb-4 event-about-image">
                        @elseif($event->banners && $event->banners->count() > 0)
                            @php
                                $primaryBanner = $event->banners->where('is_primary', true)->first();
                                $bannerImage = $primaryBanner ?: $event->banners->first();
                            @endphp
                            @if($bannerImage)
                                <img src="{{ asset('storage/' . $bannerImage->image_path) }}" alt="{{ $event->title }}" class="img-fluid rounded mb-4 event-about-image">
                            @endif
                        @endif

                        <div class="event-description" style="margin-top: 3% !important;">
                            @php
                                $plainText = strip_tags($event->description);
                                $needsTruncation = strlen($plainText) > 280;
                                $shortText = Str::limit($plainText, 280);
                            @endphp

                            <div class="description-preview">
                                {!! Str::markdown($shortText) !!}
                                @if($needsTruncation)
                                    <span class="text-muted more-link" style="cursor: pointer; font-weight: 500;">
                                        ... <a href="javascript:void(0)" class="text-primary text-decoration-none">See More</a>
                                    </span>
                                @endif
                            </div>

                            @if($needsTruncation)
                                <div class="description-full" style="display: none;">
                                    {!! Str::markdown($event->description) !!}
                                    <div class="mt-2">
                                        <a href="javascript:void(0)" class="text-primary text-decoration-none less-link" style="font-weight: 500;">
                                            See Less
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Schedule Card (Optional) --}}
                @if($event->event_schedule)
                    <div class="card event-info-card mb-4">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Event Schedule</h4>
                        </div>
                        <div class="card-body">
                            <div class="event-schedule">
                                {!! Str::markdown($event->event_schedule) !!}
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Terms Card (Optional) --}}
                @if($event->terms_and_conditions)
                    <div class="card event-info-card mb-4">
                        <div class="card-header">
                            <h4 class="card-title mb-0">Terms & Conditions</h4>
                        </div>
                        <div class="card-body">
                            <div class="terms-conditions">
                                {!! Str::markdown($event->terms_and_conditions) !!}
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Registration Card (Sticky) -->
            <div class="col-lg-4">
                <?php /*@if($event->registration_amount > 0) */?>
                    <div class="card registration-card sticky-top">
                        <div class="card-header text-center" style="margin-bottom: 13px !important;">
                            <h4 class="card-title mb-0">Book Now</h4>
                        </div>
                        <div class="card-body mb-3" style="padding: 10px !important;">
                            <div class="price-tag mb-3">

                                {{-- GLOBAL FLASH MESSAGES --}}
                                @if (session('success'))
                                    <div class="alert alert-warning text-center">
                                        {{ session('success') }}
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger text-center">
                                        {{ session('error') }}
                                    </div>
                                @endif

                                @if (session('info'))
                                    <div class="alert alert-warning text-center">
                                        {{ session('info') }}
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-danger text-center">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <span class="amount" id="schoolTypePrice">
                                    ₹{{ $event->registration_amount > 0 ? $event->registration_amount : '0.00' }}
                                </span>

                                <span class="per-person">per participant</span>
                            </div>
                            <hr class="my-3">
                            @if($event->end_date < now())
                                <div class="alert alert-warning text-center">Registration for this event has closed.</div>
                            @elseif($event->seat_limit && $event->registrations()->where('payment_status','paid')->count() >= $event->seat_limit)
                                <div class="alert alert-warning text-center">This event is currently full.</div>
                            @else
                                @auth
                                    @php
                                        $pendingRegistration = App\Models\EventRegistration::where('event_id', $event->id)
                                            ->where('user_id', Auth::id())
                                            ->where('payment_status', 'pending')
                                            ->first();
                                        $completedRegistration = App\Models\EventRegistration::where('event_id', $event->id)
                                            ->where('user_id', Auth::id())
                                            ->where('payment_status', 'paid')
                                            ->first();
                                    @endphp

                                    @if($completedRegistration)
                                        <div class="alert alert-warning text-center">
                                            You are already registered!
                                            <a href="{{ route('user.events.my-registrations') }}" class="btn btn-sm btn-outline-success mt-2 d-block">View My Registrations</a>
                                        </div>
                                    @elseif($pendingRegistration)
                                        <div class="alert alert-warning text-center">
                                            You have a pending registration!
                                            <a href="{{ route('events.payment.show', $pendingRegistration->id) }}" class="btn btn-primary btn-sm mt-2 d-block">Complete Payment</a>
                                        </div>
                                    @else
                                        @include('public.events.partials.registration-form-new', ['event' => $event, 'user' => Auth::user()])
                                    @endif
                                @else
                                    <div class="alert alert-info text-center mb-3">
                                        Existing user? <a href="{{ route('login') }}">Login</a> to register faster.
                                    </div>
                                    @include('public.events.partials.registration-form-new', ['event' => $event, 'user' => null])
                                @endauth
                            @endif
                        </div>
                    </div>
                <?php /*@else
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
                                    <p>{{ $event->start_date->format('h:i A') }} - {{ $event->end_date->format('h:i A') }}</p>
                                </div>
                                <div class="event-detail-item mb-3">
                                    <i class="fa-solid fa-location-dot"></i>
                                    <strong>Location:</strong>
                                    <p>{{ $event->venue }}</p>
                                </div>
                                @if($event->seat_limit)
                                    <div class="event-detail-item mb-3">
                                        <i class="fa-solid fa-users"></i>
                                        <strong>Capacity:</strong>
                                        <p>{{ $event->seat_limit }} seats</p>
                                    </div>
                                @endif
                                <div class="alert alert-info text-center mt-3">
                                    This is an informational event. No registration required.
                                </div>
                            </div>
                        </div>
                    </div>
                @endif */?>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const dropdown = document.getElementById("age_category");
            const priceElement = document.getElementById("schoolTypePrice");

            function updatePrice() {
                if (!dropdown || !priceElement) return;
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                const price = selectedOption.getAttribute("data-price");
                priceElement.textContent = price ? "₹" + new Intl.NumberFormat('en-IN').format(price) : "--";
            }
            if (dropdown) {
                dropdown.addEventListener("change", updatePrice);
                updatePrice();
            }

            // See More / See Less Toggle
            document.querySelectorAll('.more-link a').forEach(link => {
                link.addEventListener('click', function () {
                    const preview = this.closest('.description-preview');
                    const full = preview.nextElementSibling;
                    if (full && full.classList.contains('description-full')) {
                        preview.style.display = 'none';
                        full.style.display = 'block';
                    }
                });
            });

            document.querySelectorAll('.less-link').forEach(link => {
                link.addEventListener('click', function () {
                    const full = this.closest('.description-full');
                    const preview = full.previousElementSibling;
                    if (preview && preview.classList.contains('description-preview')) {
                        full.style.display = 'none';
                        preview.style.display = 'block';
                    }
                });
            });
        });
    </script>
@endsection