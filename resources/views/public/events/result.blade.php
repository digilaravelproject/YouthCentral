@extends('layouts.app-public')
@section('title', $event->title . ' - Event Result')

@section('content')

<style>

    body {
        overflow-x: hidden;
        overflow-y: auto !important;
    }

    .result-container {
        padding: 60px 0;
        background-color: #f8f9fa;
    }

    .result-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.08);
        padding: 40px;
        margin-bottom: 30px;
    }

    /* Make description tables responsive */
    .result-card table {
        width: 100% !important;
        display: block;
        overflow-x: auto;
        border-collapse: collapse;
    }

    .result-card table th,
    .result-card table td {
        padding: 8px;
        border: 1px solid #dee2e6;
        white-space: nowrap;
    }

    /* Custom Slider */
    .custom-slider {
        position: relative;
        width: 100%;
        height: 450px;
        overflow: hidden;
        border-radius: 12px;
        margin-bottom: 40px;
    }

    .custom-slide {
        position: absolute;
        width: 100%;
        height: 100%;
        opacity: 0;
        transition: opacity 0.6s ease-in-out;
    }

    .custom-slide.active {
        opacity: 1;
        position: relative;
    }

    .custom-slide img {
        width: 100%;
        height: 100%;
        object-fit: contain; /* prevent crop */
        background: #000;
    }

    .slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(0,0,0,0.5);
        color: white;
        border: none;
        padding: 12px 16px;
        cursor: pointer;
        font-size: 18px;
        border-radius: 50%;
        z-index: 10;
    }

    .prev-btn { left: 15px; }
    .next-btn { right: 15px; }

    @media (max-width: 768px) {
        .custom-slider {
            height: 250px;
        }
    }

</style>

<div class="result-container">
    <div class="container">
        <div class="result-card">

            @php
                $images = $event->additional_images;

                if (is_string($images)) {
                    $images = json_decode($images, true);
                }

                if (!is_array($images)) {
                    $images = [];
                }
            @endphp

            @if(count($images) > 0)
                <div class="custom-slider" id="customSlider">

                    @foreach($images as $index => $image)
                        <div class="custom-slide {{ $index == 0 ? 'active' : '' }}">
                            <img src="{{ asset('storage/' . $image) }}">
                        </div>
                    @endforeach

                    @if(count($images) > 1)
                        <button class="slider-btn prev-btn" onclick="prevSlide()">❮</button>
                        <button class="slider-btn next-btn" onclick="nextSlide()">❯</button>
                    @endif

                </div>
            @endif

            <!-- <h1 class="mt-4">{{ $event->title }}</h1> -->
             <h1 class="mt-4">
                {{ str_replace('2027', '2026', $event->title) }}
            </h1>

            <div class="mt-4">
                @if($event->long_description)
                    {!! $event->long_description !!}
                @else
                    <p class="text-muted">No additional description available.</p>
                @endif
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const slider = document.getElementById("customSlider");

    if (!slider) return;

    const slides = slider.querySelectorAll('.custom-slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }

    window.nextSlide = function() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    window.prevSlide = function() {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        showSlide(currentSlide);
    }

    if (slides.length > 1) {
        setInterval(window.nextSlide, 3000);
    }

});
</script>

@endsection