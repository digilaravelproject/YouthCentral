@extends('layouts.app-public')

@section('title', 'Youth Central - Business Directory')

@push('styles')
    <style>

        .listing-item-address:hover {
            color: #fff !important;
            text-decoration: none;    /* optional */
        }

        /* Fix for business images to always be round */
        .listing-item-link img,
        .front-category-businesses img,
        .front-trending img {
            width: 300px;
            height: 300px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
        }

        .image-container {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 50%;
        }
        
        .row-title { 
            margin-top: 30px !important;
            margin-bottom: 15px !important;
        }
        
        .trending {
            margin-top: 15px !important;
        }

        /* --- FIX: High Contrast for Search Categories Icons --- */
        .search-categories .categories .child {
            background: rgba(0, 0, 0, 0.65); /* Dark background for visibility */
            padding: 8px 15px;
            border-radius: 30px; /* Pill shape */
            margin: 5px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .search-categories .categories .child:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .search-categories .categories .child span {
            color: #fff;
            font-weight: 500;
            font-size: 14px;
        }

        .search-categories .categories .child i {
            color: #fff;
            font-size: 16px;
            margin-right: 8px; /* Spacing between icon and text */
        }

        /* --- FIX: Typing Effect Glitch --- */
        .typingEffect {
            white-space: nowrap; /* Prevent text wrapping/cutting */
            overflow: hidden;
            text-overflow: ellipsis;
            display: inline-block;
            vertical-align: bottom;
            max-width: 85%; /* Ensure it fits in container on mobile */
        }

        /* Additional Styles for new sections */
        .search-cities .cities-list a.current {
            border: 2px solid var(--primary-color);
        }

        .search-cities .cities-list a:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }

        .how-item {
            background-color: var(--primary-darker);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .how-item:hover {
            transform: translateY(-5px);
        }

        .how-item img {
            max-width: 80px;
            margin-bottom: 20px;
        }

        .how-item-title {
            margin-bottom: 15px;
            color: #333;
        }

        .how-item-excerpt {
            color: #fffcf2;
            font-size: 14px;
            line-height: 1.6;
        }

        /* JD Slider Section */
        .jd-slider-section {
            padding-top: 90px; /* Menu ke neeche se bachne ke liye padding */
            padding-bottom: 30px;
            background-color: #f9f9f9;
        }

        /* Card Container - Banner Style */
        .jd-card {
            display: block;
            position: relative;
            height: 220px; /* Thoda height badhaya taaki banner bada dikhe */
            border-radius: 15px; /* Thoda zyada rounded corners */
            overflow: hidden;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15); /* Stronger shadow for pop */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .jd-card:hover {
            /* transform: translateY(-5px); */
            /* transform: scale(1.03); */
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        /* Background Image - Full Visibility */
        .jd-card-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 1; /* Ab image full dikhegi (no fading) */
            transition: transform 0.5s ease; /* Zoom effect ke liye */
        }

        .jd-card:hover .jd-card-bg {
            transform: scale(1.1); /* Hover karne par image zoom hogi */
        }

        /* Overlay - Gradient for Text Readability */
        .jd-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Niche dark gradient taaki text safed mein chamke */
            background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.2) 60%, rgba(0, 0, 0, 0.9) 100%);
        }

        /* Icon Styling */
        .jd-card-icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 28px; /* Thoda bada icon */
            color: #fff;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.5);
            z-index: 2;
            background: rgba(255, 255, 255, 0.2); /* Icon ke peeche halka glass effect */
            padding: 8px;
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(4px);
        }

        /* Text Content */
        .jd-card-content {
            position: absolute;
            bottom: 20px;
            left: 20px;
            z-index: 2;
            color: #fff;
            width: 85%;
        }

        .jd-card-title {
            font-size: 22px; /* Bada title font */
            font-weight: 800;
            margin-bottom: 4px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
            line-height: 1.1;
            letter-spacing: -0.5px;
        }

        .jd-card-subtitle {
            font-size: 14px;
            font-weight: 500;
            opacity: 0.9;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.5);
        }

        /* Just Dial Mobile Style Tweaks */
        @media (max-width: 768px) {
            .jd-slider-section {
                padding-top: 100px; /* Mobile par padding */
                padding-bottom: 20px;
            }

            .jd-card {
                height: 160px; /* Mobile par height thodi adjust ki */
                border-radius: 12px;
            }

            .jd-card-title {
                font-size: 18px;
            }

            .jd-card-subtitle {
                font-size: 12px;
            }

            .jd-card-icon {
                font-size: 20px;
                width: 35px;
                height: 35px;
                top: 10px;
                right: 10px;
            }
        }
    /* 1. Section Container */
    .fresh-hero-section {
        padding: 80px 0 100px 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        position: relative;
        text-align: center;
        width: 100%;
        overflow: hidden;
    }

    /* 2. Typography */
    .fresh-hero-section .hero-title {
        font-size: 3rem;
        font-weight: 800;
        color: #2d3436;
        margin-bottom: 15px;
        letter-spacing: -1px;
        line-height: 1.2;
        animation: slideDown 0.8s ease-out;
    }

    .fresh-hero-section .hero-subtitle {
        font-size: 1.1rem;
        color: #636e72;
        margin-bottom: 40px;
        font-weight: 500;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
        animation: slideDown 1s ease-out;
    }

    /* 3. MODERN SEARCH BAR (FIXED) */
    .fresh-search-container {
        max-width: 800px;
        width: 90%; /* Responsive Width */
        margin: 0 auto 60px;
        position: relative;
        z-index: 10;
        animation: zoomIn 0.8s ease;
    }

    /* Main Box Styling */
    .fresh-search-box {
        background: #ffffff;
        padding: 6px; /* Space for button inside */
        border-radius: 60px; /* Pill Shape */
        box-shadow: 0 15px 35px rgba(0,0,0,0.1); /* Soft Shadow */
        display: flex;
        align-items: center;
        border: 1px solid #e1e1e1;
        transition: all 0.3s ease;
        position: relative;
        height: 70px; /* Fixed robust height */
    }

    .fresh-search-box:hover,
    .fresh-search-box:focus-within {
        transform: translateY(-2px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        border-color: var(--primary-color);
    }

    /* Search Icon */
    .search-icon-wrapper {
        padding-left: 25px;
        padding-right: 15px;
        color: #b2bec3;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .search-icon-wrapper i {
        font-size: 1.4rem;
    }

    /* Input Field */
    .fresh-search-box .form-control {
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
        height: 100%;
        font-size: 1.1rem;
        padding: 0;
        color: #2d3436;
        font-weight: 500;
        flex-grow: 1; /* Takes all available space */
        outline: none;
    }

    .fresh-search-box .form-control::placeholder {
        color: #b2bec3;
        font-weight: 400;
    }

    /* Submit Button (Actual Button) */
    .search-btn {
        background: var(--primary-color);
        color: white;
        height: 58px; /* Fits inside the 70px container with padding */
        border-radius: 50px;
        padding: 0 40px;
        font-weight: 700;
        font-size: 16px;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        transition: background 0.3s ease;
        flex-shrink: 0; /* Prevents button from squishing */
        margin-right: 1px;
    }

    .search-btn:hover {
        background: #2d3436; /* Dark on hover */
    }

    .search-btn i {
        font-size: 18px;
    }

    /* Typing Effect Position */
    .fresh-search-container .typingEffect {
        position: absolute;
        bottom: -30px;
        left: 0;
        width: 100%;
        text-align: center;
        font-size: 0.9rem;
        color: #888;
        font-style: italic;
    }

    /* 4. Categories Pills */
    .fresh-categories-wrapper {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 10px;
    }

    .fresh-category-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        padding: 0 20px;
        height: 50px;
        border-radius: 12px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        text-decoration: none !important;
        color: #636e72;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        min-width: 140px;
    }

    .fresh-category-pill i {
        font-size: 18px;
        color: var(--primary-color);
        margin-right: 10px;
    }

    .fresh-category-pill:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
        border-color: var(--primary-color);
        color: var(--primary-color);
    }

    /* --- RESPONSIVE FIXES --- */
    @media (max-width: 768px) {
        .fresh-hero-section { padding: 50px 0; }

        .fresh-hero-section .hero-title {
            font-size: 2rem;
            padding: 0 15px;
        }

        /* Mobile Search Box */
        .fresh-search-box {
            height: 60px; /* Slightly smaller height */
            padding: 4px;
        }

        .search-icon-wrapper {
            padding-left: 15px;
            padding-right: 10px;
        }

        .search-icon-wrapper i { font-size: 1.2rem; }

        .fresh-search-box .form-control {
            font-size: 1rem;
        }

        /* Button becomes Circle on Mobile */
        .search-btn {
            width: 52px;
            height: 52px;
            padding: 0;
            justify-content: center;
            border-radius: 50%;
        }

        .search-btn span {
            display: none; /* Hide text "Search" */
        }

        .search-btn i {
            margin: 0;
            font-size: 20px;
        }

        /* Category Pills Mobile */
        .fresh-category-pill {
            width: calc(50% - 10px); /* 2 per row */
            min-width: auto;
            height: 45px;
            padding: 0 10px;
            font-size: 12px;
        }
    }
    </style>
@endpush

@section('content')

    <!-- Slider Start - Just Dial Style -->
    <!-- Added Extra Padding via CSS to prevent Menu Overlap -->
    <div class="container-fluid jd-slider-section">
        <div class="container-fluid">

            <!-- PHP Array for Dynamic Data Structure -->
            @php
                // DB-driven slider items (managed from Admin -> Static Content -> Homepage Slider)
                $sliderItems = $sliderItems ?? collect();
            @endphp

            <div class="owl-carousel jd-slider owl-theme">
                @foreach ($sliderItems as $item)
                    <div class="item">
                        <a href="{{ $item->link_url ?: '#' }}" class="jd-card" style="background-color: {{ $item->color ?? '#0a0f1f' }};">
                            <!-- Background Image -->
                            <div class="jd-card-bg" style="background-image: url('{{ $item->image_url }}');"></div>

                            <div class="jd-card-overlay"></div>

                            @if($item->icon_class)
                                <i class="{{ $item->icon_class }} jd-card-icon"></i>
                            @endif

                            <div class="jd-card-content">
                                <div class="jd-card-title">{{ $item->title }}</div>
                                <div class="jd-card-subtitle">{{ $item->subtitle }}</div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
    <!-- Slider End -->


<div class="container-fluid fresh-hero-section">
    <div class="container">

        <!-- Title -->
        <h2 class="hero-title">Dream Bigger, Start Here</h2>
        <p class="hero-subtitle">
            Find the best Tutors, Sports Academies, Music Classes, and Doctors near you.
        </p>

        <!-- NEW SEARCH BAR STRUCTURE -->
        <div class="fresh-search-container">
            <form action="{{ route('search') }}" method="GET">

                <!-- The Search Box Wrapper -->
                <div class="fresh-search-box">

                    <!-- Icon -->
                    <div class="search-icon-wrapper">
                        <i class="fi fi-rr-search"></i>
                    </div>

                    <!-- Input -->
                    <input type="text" name="query" class="form-control"
                           placeholder="What are you looking for?"
                           autocomplete="off">

                    <!-- Simple Button -->
                    <button type="submit" class="search-btn">
                        <span style="color: white">Search</span>
                        <i class="fi fi-rr-arrow-right"></i>
                    </button>

                </div>

                <!-- Hidden Logic Elements (Do Not Remove - JS needs these) -->
                <div class="search-cities-toggle" style="display:none;"></div>
                <div class="search-cities" style="display:none;">
                    <div class="cities-list">
                        @foreach ($popularCities as $index => $city)
                            <a href="#" data-city-id="{{ $city->id }}" {{ $index === 0 ? 'class="current"' : '' }}>
                                <span>{{ $city->name }}</span>
                            </a>
                        @endforeach
                        <input class="chosen-city" type="hidden" name="city" value="">
                    </div>
                </div>

            </form>

            <!-- Typing Effect -->
            <span class="typingEffect"
                  data-title="Explore Opportunities//Explore Your Passion//Explore Yourself"></span>
        </div>

        <!-- Categories Grid -->
        <div class="fresh-categories-wrapper">
            @php
                // Mapping Icons & Search Terms
                $customCategoryMappings = [
                    'Tuitions' => ['icon' => 'fi fi-rr-book-alt', 'search_terms' => ['tuition', 'tutoring', 'tutor']],
                    'Football/Soccer' => ['icon' => 'fi fi-rr-football-player', 'search_terms' => ['football', 'soccer']],
                    'Cricket' => ['icon' => 'fi fi-rs-cricket', 'search_terms' => ['cricket']],
                    'Swimming' => ['icon' => 'fi fi-rs-swimmer', 'search_terms' => ['swimming', 'swim']],
                    'Coaching' => ['icon' => 'fi fi-rr-workshop', 'search_terms' => ['coaching', 'classes', 'training']],
                    'Computers/AI' => ['icon' => 'fi fi-rs-computer', 'search_terms' => ['computer', 'ai', 'programming']],
                    'Theatre' => ['icon' => 'fi fi-ts-theater-masks', 'search_terms' => ['theatre', 'acting', 'drama']],
                    'Music' => ['icon' => 'fi fi-rs-music-alt', 'search_terms' => ['music', 'musical']],
                    'Day Care' => ['icon' => 'fi fi-rr-child', 'search_terms' => ['daycare', 'childcare']],
                    'Martial Arts' => ['icon' => 'fi fi-tr-uniform-martial-arts', 'search_terms' => ['martial arts', 'karate']],
                    'Stem' => ['icon' => 'fi fi-rr-microscope', 'search_terms' => ['stem', 'science']],
                    'Library' => ['icon' => 'fi fi-rr-book-alt', 'search_terms' => ['library']],
                    'Pediatrician' => ['icon' => 'fi fi-rr-stethoscope', 'search_terms' => ['pediatrician']],
                    'Painting' => ['icon' => 'fi fi-rr-palette', 'search_terms' => ['painting', 'art']],
                ];

                // Logic to build the list
                $customCategories = [];
                foreach ($customCategoryMappings as $categoryName => $mapping) {
                    $subcategory = null;
                    // Attempt to find dynamic link
                    foreach ($mapping['search_terms'] as $term) {
                        $subcategory = \App\Models\Subcategory::where('name', 'LIKE', '%' . $term . '%')->first();
                        if ($subcategory) break;
                    }

                    if ($subcategory) {
                        $customCategories[] = [
                            'name' => $categoryName,
                            'icon' => $subcategory->icon_class ? $subcategory->getFormattedIconClass() : $mapping['icon'],
                            'route' => route('listings', $subcategory),
                        ];
                    } else {
                        $customCategories[] = [
                            'name' => $categoryName,
                            'icon' => $mapping['icon'],
                            'route' => route('search') . '?query=' . urlencode($categoryName),
                        ];
                    }
                }
            @endphp

            @foreach ($customCategories as $category)
                <a class="fresh-category-pill" href="{{ $category['route'] }}">
                    <i class="{{ $category['icon'] }}"></i>
                    <span>{{ $category['name'] }}</span>
                </a>
            @endforeach

            <a href="{{ route('directory.index') }}" class="fresh-category-pill" style="background: #2d3436; color: #fff !important; border-color: #2d3436;">
                <i class="fi fi-rr-apps" style="color: #fff;"></i>
                <span style="color: #fff !important;">More Options</span>
            </a>
        </div>

    </div>
</div>

    <!-- Start Hero Header (Video Section) - Restored to original position below slider -->
    <div class="container-fluid hero-header h-video transparent" style="display: none">
        <!-- Start Header Centralizer -->
        <div class="header-centralizer">
            <div class="content-centralized">
                <!-- Start Hero Search -->
                <div class="hero-search">
                    <form action="{{ route('search') }}" method="GET">
                        <fieldset>
                            <input type="text" name="query" class="form-control"
                                data-placeholder="Explore and Enjoy...">
                            <span class="typingEffect"
                                data-title="Dream Bigger, Start Here//Explore Opportunities//Explore Your Passion"></span>
                        </fieldset>
                        <!-- Start Search cities Toggle -->
                        <div class="search-cities-toggle"></div>
                        <!-- End Search cities Toggle -->
                        <!-- Start Search Cities -->
                        <div class="search-cities">
                            <div class="cities-list">
                                @foreach ($popularCities as $index => $city)
                                    <a href="#"
                                        style="background-image:url('{{ asset('assets_public/images/cities/thumbs/' . (($index % 5) + 1) . '.jpg') }}')"
                                        data-city-id="{{ $city->id }}"
                                        {{ $index === 0 ? 'class="current"' : '' }}><span>{{ $city->name }}</span></a>
                                @endforeach
                                <input class="chosen-city" type="hidden" name="city" value="">
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
                                    'icon' => 'fi fi-rr-book-alt',
                                    'search_terms' => ['tuition', 'tutoring', 'tutor'],
                                ],
                                'Football/Soccer' => [
                                    'icon' => 'fi fi-rr-football-player',
                                    'search_terms' => ['football', 'soccer'],
                                ],
                                'Cricket' => ['icon' => 'fi fi-rs-cricket', 'search_terms' => ['cricket']],
                                'Swimming' => ['icon' => 'fi fi-rs-swimmer', 'search_terms' => ['swimming', 'swim']],
                                'Coaching Classes' => [
                                    'icon' => 'fi fi-rr-workshop',
                                    'search_terms' => ['coaching', 'classes', 'training'],
                                ],
                                'Computers/AI' => [
                                    'icon' => 'fi fi-rs-computer',
                                    'search_terms' => ['computer', 'ai', 'programming', 'coding'],
                                ],
                                'Theatre/Acting' => [
                                    'icon' => 'fi fi-ts-theater-masks',
                                    'search_terms' => ['theatre', 'acting', 'drama'],
                                ],
                                'Music' => ['icon' => 'fi fi-rs-music-alt', 'search_terms' => ['music', 'musical']],
                                'Day Care' => [
                                    'icon' => 'fi fi-rr-child',
                                    'search_terms' => ['daycare', 'day care', 'childcare'],
                                ],
                                'Chess' => ['icon' => 'fi fi-ts-chess', 'search_terms' => ['chess']],
                                'Table Tennis' => [
                                    'icon' => 'fi fi-rr-ping-pong',
                                    'search_terms' => ['table tennis', 'ping pong'],
                                ],
                                'Martial Arts/Karate' => [
                                    'icon' => 'fi fi-tr-uniform-martial-arts',
                                    'search_terms' => ['martial arts', 'karate', 'taekwondo'],
                                ],
                                'Foundational Stem' => [
                                    'icon' => 'fi fi-rr-microscope',
                                    'search_terms' => ['stem', 'science', 'foundational'],
                                ],
                                'Maths/Science' => [
                                    'icon' => 'fi fi-sr-calculator-simple',
                                    'search_terms' => ['math', 'science', 'mathematics'],
                                ],
                                'Library' => [
                                    'icon' => 'fi fi-rr-book-alt',
                                    'search_terms' => ['library', 'libraries'],
                                ],
                                'Pediatrician' => [
                                    'icon' => 'fi fi-rr-stethoscope',
                                    'search_terms' => ['pediatrician', 'pediatric'],
                                ],
                                'Counselling' => [
                                    'icon' => 'fi fi-rr-meeting',
                                    'search_terms' => ['counselling', 'counseling', 'therapy'],
                                ],
                                'Painting/Sketching' => [
                                    'icon' => 'fi fi-rr-palette',
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
                        <a href="{{ route('directory.index') }}" class="child">
                            <i class="fi fi-rr-apps"></i>
                            <span>More Options</span>
                        </a>
                    </div>
                </div>
                <!-- End Search Categories -->
            </div>
        </div>
    </div>
    <!-- Ends Hero Header -->

    <!-- Start Front Categories -->
    <div class="container-fluid front-categories vertical">
        <div class="row row-title">
            <h1>What Do You Need Today?</h1>
            <h4>Tutors, Sports, Music, Doctors - One Click Away</h4>
        </div>
        <div class="row cat-itens">
            @foreach ($categories->take(5) as $index => $category)
                <div class="cat-item">
                    <div class="cat-overlay"></div>
                    <div class="cat-image"
                        style="background-image:url('{{ $category->image ? asset($category->image) : asset('assets_public/images/categories/' . ($index + 1) . '.jpg') }}')">
                    </div>
                    <div class="cat-icon" style="padding-top: 15px;">
                        <i class="{{ $category->getFormattedIconClass() }}"></i>
                    </div>
                    <div class="cat-counter">{{ $category->subcategories->count() }}</div>
                    <a href="{{ route('public.category', $category) }}" style="align-content: center;">
                        <div class="cat-text">{{ $category->name }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- End Front Categories -->

    <!-- Start Trending -->
    <div class="container-fluid trending">
        <div class="row row-title">
            <h1 style="color: white"> Trending Right Now</h1>
            <h4>Most Viewed Recently</h4>
        </div>
        <div class="row listing">
            @foreach ($trendingBusinesses as $index => $business)
                <!-- Start Trending Item Col -->
                <div class="col-sm-3">
                    <div class="listing-item">
                        <a href="#" class="category-icon">
                            <i
                                class="{{ $business->subcategory ? $business->subcategory->getFormattedIconClass() : 'fas fa-bookmark' }}"></i>
                        </a>
                        <div class="listing-item-rating">
                            {{ $business->average_rating ? number_format($business->average_rating, 1) : '4.0' }}</div>
                        <a href="{{ route('public.business.show', $business) }}" class="listing-item-link">
                            <div class="listing-item-title-centralizer">
                                <div class="listing-item-title">
                                    {{ $business->business_name }}
                                    <div class="ribbon">
                                        @if ($business->price_range)
                                            {{ $business->price_range }}
                                        @else
                                            ~ ${{ rand(20, 50) }}-${{ rand(50, 100) }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="image-wrapper">
                                @php
                                    $primaryImage = $business->images()->where('is_primary', true)->first();
                                    $firstImage = $business->images()->first();
                                    $imageUrl = asset('assets_public/images/listings/' . (($index % 8) + 1) . '.jpg'); // Default placeholder from listings

                                    if ($primaryImage && Storage::disk('public')->exists($primaryImage->path)) {
                                        $imageUrl = asset('storage/' . $primaryImage->path);
                                    } elseif ($firstImage && Storage::disk('public')->exists($firstImage->path)) {
                                        $imageUrl = asset('storage/' . $firstImage->path);
                                    }
                                @endphp
                                <img alt="{{ $business->business_name }}" src="{{ $imageUrl }}" />
                            </div>
                        </a>
                        <div class="listing-item-data">
                            <a class="listing-item-address" href="#">
                                {{ $business->street_address }}@if ($business->area)
                                    , {{ $business->area->name }}
                                @endif
                            </a>
                            <div class="listing-item-excerpt">
                                {{ Str::limit($business->description, 60) ?? 'Visit our business for the best experience' }}
                            </div>
                        </div>
                        <div class="listing-category-name">
                            <a
                                href="#">{{ $business->subcategory ? $business->subcategory->name : 'Uncategorized' }}</a>
                        </div>
                    </div>
                </div>
                <!-- End Trending Item Col -->
            @endforeach
        </div>
    </div>
    <!-- End Trending -->

    <!-- Start How It Works -->
    <div class="container-fluid how color-bg">
        <div class="row row-title">
            <h1>How it Works</h1>
            <h4>If you love to cater to our Young Ones, List your business today on Youth Central, to reach out to India's
                largest community of Students and Parents</h4>
        </div>
        <div class="row how-items">
            <!-- Start How Item Col -->
            <div class="col-sm-3">
                <div class="how-item">

                    <div class="image-wrapper">
                        <img alt="" src="{{ asset('assets_public/images/miscellaneous/icon-1.png') }}" />
                    </div>
                    <h3 class="how-item-title">Businesses/Vendors</h3>
                    <div class="how-item-excerpt">
                        Register your classes, Activities, or Services on Youth Central. Share details, photos, and what
                        makes your business special — so more families can find you.
                    </div>

                </div>
            </div>
            <!-- End How Item Col -->
            <!-- Start How Item Col -->
            <div class="col-sm-3">
                <div class="how-item">

                    <div class="image-wrapper">
                        <img alt="" src="{{ asset('assets_public/images/miscellaneous/icon-2.png') }}" />
                    </div>
                    <h3 class="how-item-title">Users</h3>
                    <div class="how-item-excerpt">
                        Find the best services near you in ONE CLICK.
                        Tuitions, Sports, Art, Dance, Music, or Health Services for your child, find everything for your
                        young ones need at one platform.
                    </div>

                </div>
            </div>
            <!-- End How Item Col -->
            <!-- Start How Item Col -->
            <div class="col-sm-3">
                <div class="how-item">

                    <div class="image-wrapper">
                        <img alt="" src="{{ asset('assets_public/images/miscellaneous/icon-3.png') }}" />
                    </div>
                    <h3 class="how-item-title">YC Community</h3>
                    <div class="how-item-excerpt">
                        Connect, Learn, and Grow Together.
                        Parents, Kids, and Businesses can connect with each other. Ask questions, join events, and be part
                        of a growing Youth Central family.
                    </div>

                </div>
            </div>
            <!-- End How Item Col -->
            <!-- Start How Item Col -->
            <div class="col-sm-3">
                <div class="how-item">

                    <div class="image-wrapper">
                        <img alt="" src="{{ asset('assets_public/images/miscellaneous/icon-4.png') }}" />
                    </div>
                    <h3 class="how-item-title">Free Vs Premium</h3>
                    <div class="how-item-excerpt">
                        Register or Claim your business for Free. Go premium for more benefits.
                        Businesses can join for free. Premium members get more visibility, full business profile, and are
                        shown at the top when users search.
                    </div>

                </div>
            </div>
            <!-- End How Item Col -->
        </div>
    </div>
    <!-- End How It Works -->

    <!-- Start Events Section -->
    <section class="section events-slider" id="events">
        @php
            $events = \App\Models\Event::with([
                'banners' => function ($query) {
                    $query->where('is_primary', true);
                },
            ])
                ->where('status', 'approved')
                ->where('end_date', '>=', now())
                ->orderBy('start_date')
                ->take(5)
                ->get();
        @endphp
    
        @if ($events->count() > 0)
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <h2 class="section-title text-center mb-5">Upcoming Events</h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="owl-carousel events-carousel owl-theme">
                            @foreach ($events as $event)
                                <div class="event-card item">
                                    <div class="event-image">
                                        @if ($event->banner_image)
                                            <img src="{{ asset('storage/' . $event->banner_image) }}"
                                                alt="{{ $event->title }}" class="img-fluid">
                                        @elseif($event->banners && $event->banners->where('is_primary', true)->first())
                                            <img src="{{ asset('storage/' . $event->banners->where('is_primary', true)->first()->image_path) }}"
                                                alt="{{ $event->title }}" class="img-fluid">
                                        @else
                                            <img src="{{ asset('assets_public/images/blog/1.jpg') }}"
                                                alt="Default Event Image" class="img-fluid">
                                        @endif
                                        <div class="event-date">
                                            <span class="day">{{ $event->start_date->format('d') }}</span>
                                            <span class="month">{{ $event->start_date->format('M') }}</span>
                                        </div>
                                    </div>
                                    <div class="event-content">
                                        <h3>{{ $event->title }}</h3>
                                        <div class="event-meta">
                                            <span><i class="fa-solid fa-location-dot"></i> {{ $event->venue }}</span>
                                            <span><i class="fa-regular fa-clock"></i>
                                                {{ $event->start_date->format('h:i A') }}</span>
                                        </div>
                                        <p class="event-description">{{ Str::limit($event->description, 100) }}</p>
                                        <div class="event-footer">
                                            <span
                                                class="price">₹{{ number_format($event->registration_amount, 2) }}</span>
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-primary">View
                                                Details</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <style>
            .events-slider {
                /*padding: 60px 0;*/
                background: #f8f9fa;
                position: relative;
                min-height: 400px;
                overflow: visible;
            }

            .events-carousel.owl-carousel.owl-theme {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                height: auto !important;
                overflow: visible;
                position: relative;
                z-index: 1;
            }

            .event-card.item {
                display: block !important;
                opacity: 1 !important;
                visibility: visible !important;
                background: #fff;
                border-radius: 10px;
                overflow: hidden;
                box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
                margin: 10px;
                transition: transform 0.3s ease;
            }

            .event-card:hover {
                transform: translateY(-5px);
            }

            .event-image {
                position: relative;
                height: 250px;
                overflow: hidden;
            }

            .event-image img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .event-date {
                position: absolute;
                top: 20px;
                right: 20px;
                background: rgba(255, 255, 255, 0.95);
                padding: 15px;
                border-radius: 8px;
                text-align: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .event-date .day {
                font-size: 28px;
                font-weight: bold;
                display: block;
                color: #333;
                line-height: 1;
            }

            .event-date .month {
                font-size: 16px;
                color: #666;
                text-transform: uppercase;
                margin-top: 5px;
            }

            .event-content {
                padding: 25px;
            }

            .event-content h3 {
                margin: 0 0 15px;
                font-size: 22px;
                color: #333;
                font-weight: 600;
            }

            .event-meta {
                margin-bottom: 15px;
                color: #666;
            }

            .event-meta span {
                margin-right: 20px;
                font-size: 14px;
            }

            .event-meta i {
                margin-right: 5px;
                color: var(--primary-color);
            }

            .event-description {
                color: #666;
                margin-bottom: 20px;
                line-height: 1.6;
                font-size: 14px;
            }

            .event-footer {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding-top: 20px;
                border-top: 1px solid #eee;
            }

            .price {
                font-size: 24px;
                font-weight: bold;
                color: var(--primary-color);
            }

            .btn-primary {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                padding: 8px 20px;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background-color: var(--primary-color);
                border-color: var(--primary-color);
                transform: translateY(-2px);
            }

            .owl-nav {
                position: absolute;
                top: 50%;
                width: 100%;
                transform: translateY(-50%);
                display: flex;
                justify-content: space-between;
                padding: 0 15px;
                pointer-events: none;
                z-index: 10;
            }

            .owl-prev,
            .owl-next {
                width: 45px;
                height: 45px;
                background: var(--primary-color) !important;
                border-radius: 50% !important;
                display: flex !important;
                align-items: center;
                justify-content: center;
                color: white !important;
                font-size: 22px !important;
                pointer-events: auto;
                transition: background-color 0.3s ease, transform 0.3s ease;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            }

            .owl-prev {
                margin-left: -50px;
            }

            .owl-next {
                margin-right: -50px;
            }

            .owl-prev:hover,
            .owl-next:hover {
                background: var(--primary-color) !important;
                transform: scale(1.1);
            }

            .owl-dots {
                text-align: center;
                padding-top: 20px;
                padding-bottom: 20px;
                position: relative;
                z-index: 5;
            }

            .owl-dot {
                display: inline-block;
                zoom: 1;
            }

            .owl-dot span {
                width: 12px;
                height: 12px;
                margin: 5px 7px;
                background: #ccc !important;
                display: block;
                -webkit-backface-visibility: visible;
                transition: opacity 200ms ease, background-color 200ms ease, transform 200ms ease;
                border-radius: 30px;
            }

            .owl-dot.active span,
            .owl-dot:hover span {
                background: var(--primary-color) !important;
                transform: scale(1.2);
            }
        </style>
    </section>
    <!-- End Events Section -->
@endsection

<!-- YouTube Hero Video Background -->
<script>
    var yt_video = "J7NfD6MuZrk";
    var v_start = 0;
</script>

@push('scripts')
    <script>
        // YouTube video background
        var yt_video = "J7NfD6MuZrk";
        var v_start = 0;
    </script>

    <script>
        $(document).ready(function() {
            // --- Ye Code Add Karein ---
            // Initialize JD Slider (Top Categories)
            $('.jd-slider').owlCarousel({
                loop: true,
                margin: 15,
                nav: false, // Arrows removed for cleaner Just Dial look
                dots: false, // Dots removed to save space on mobile
                autoplay: true,
                autoplayTimeout: 3000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 2, // Just Dial Style: 2 items on mobile
                        stagePadding: 20, // Shows a peek of the 3rd item (very app-like)
                        margin: 10
                    },
                    600: {
                        items: 3
                    },
                    1000: {
                        items: 5
                    }
                }
            });
            // ---------------------------
            // Owl Carousel Initialization
            $('.events-carousel').owlCarousel({
                loop: true,
                margin: 20,
                nav: true,
                dots: true,
                autoplay: true,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 3
                    }
                },
                navText: ['<i class="fa-solid fa-chevron-left"></i>',
                    '<i class="fa-solid fa-chevron-right"></i>'
                ]
            });

            // Initialize typing effect
            var typingEffect = $('.typingEffect');
            if (typingEffect.length) {
                var typingStrings = typingEffect.data('title').split('//');
                new Typed('.typingEffect', {
                    strings: typingStrings,
                    typeSpeed: 100,
                    backSpeed: 50,
                    backDelay: 1500,
                    startDelay: 500,
                    loop: true
                });
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // --- Optimized City Selection Logic (No Timeouts) ---

            // Event Delegation for City Selection
            // This works even if the elements are loaded dynamically later
            $(document).on('click', '.cities-list a', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Stop bubbling

                var $this = $(this);
                var cityId = $this.attr('data-city-id');
                var $form = $this.closest('form');

                if (cityId) {
                    // Update UI
                    $form.find('.cities-list a').removeClass('current');
                    $this.addClass('current');

                    // Update Hidden Input
                    $form.find('.chosen-city').val(cityId);

                    console.log('City Selected (Delegated):', cityId);

                    // Close the cities dropdown smoothly
                    $('.hero-header').removeClass('open-cities-list');
                    $('.search-cities').stop().animate({
                        top: '-1000px'
                    }, 500, 'easeInExpo');
                }
            });

            // Event Delegation for Cities Toggle
            $(document).on('click', '.search-cities-toggle', function(e) {
                e.preventDefault();
                console.log('Homepage - Cities toggle clicked');

                var $header = $('.hero-header');
                var $citiesBox = $('.search-cities');

                // Toggle the class
                $header.toggleClass('open-cities-list');

                // Animate based on the new state
                if ($header.hasClass('open-cities-list')) {
                    // Opening
                    $citiesBox.css('top', '-1000px').stop().animate({
                        top: 0
                    }, 800, 'easeOutExpo');
                } else {
                    // Closing
                    $citiesBox.stop().animate({
                        top: '-1000px'
                    }, 800, 'easeInExpo');
                }
            });

            // Debug form submission
            $('form').on('submit', function(e) {
                var cityValue = $(this).find('.chosen-city').val();
                var query = $(this).find('input[name="query"]').val();
                console.log('Homepage - Form submitting with city:', cityValue, 'and query:', query);
                return true;
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // --- Automatic Location Detection ---
            const locationIsSet = {{ session()->has('user_location') ? 'true' : 'false' }};

            if (!locationIsSet) {
                console.log("User location not set in session. Attempting to fetch from browser.");
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        console.log(`Geolocation success. Lat: ${lat}, Lng: ${lng}`);

                        $.ajax({
                            url: '{{ route('location.update') }}',
                            method: 'POST',
                            data: {
                                latitude: lat,
                                longitude: lng,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                if (response.success) {
                                    console.log(
                                        "Location successfully updated on server. Reloading page."
                                    );
                                    window.location.reload();
                                } else {
                                    console.error("Server failed to update location:", response
                                        .message);
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error("AJAX error updating location:", error);
                            }
                        });
                    }, function(error) {
                        console.warn(
                            `Geolocation failed: ${error.message}. Location-based features will use default settings.`
                        );
                    });
                } else {
                    console.warn("Geolocation is not supported by this browser.");
                }
            } else {
                console.log("User location already set in session. Skipping geolocation fetch.");
            }
        });
    </script>
@endpush
