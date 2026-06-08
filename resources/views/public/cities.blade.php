@extends('layouts.app-public')

@section('title', 'All Cities - Youth Central')

@section('content')

<!-- Start Page Title -->
<div class="page-title" style="background-image: url('{{ asset('assets_public/images/backgrounds/8.jpg') }}');">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <h1>Explore Our Cities</h1>
        <p>Find businesses and services in your favorite locations.</p>
      </div>
    </div>
  </div>
</div>
<!-- End Page Title -->

<!-- Start Content Wrapper -->
<div class="content-wrapper">
  <div class="container">
    <div class="row">
      <!-- Start Cities Grid -->
      <div class="col-sm-12 cities-grid-full">
        <div class="row">
          @forelse($allCities as $city)
          <div class="col-sm-3">
            <div class="city-grid-item">
              <div class="city-image" style="background-image: url('{{ asset('assets_public/images/cities/' . (($loop->index % 6) + 1) . '.jpg') }}');">
                <div class="city-overlay"></div>
                <div class="city-counter">{{ $city->businesses_count }} {{ Str::plural('Listing', $city->businesses_count) }}</div>
                <a href="#" class="city-link">{{-- Link to city-specific business page? --}}
                  <div class="city-name">{{ $city->name }}</div>
                </a>
              </div>
            </div>
          </div>
          @empty
          <div class="col-12">
            <p class="text-center">No cities found.</p>
          </div>
          @endforelse
        </div>
      </div>
      <!-- End Cities Grid -->
    </div>
  </div>
</div>
<!-- End Content Wrapper -->

<style>
.page-title {
  padding: 100px 0;
  background-size: cover;
  background-position: center;
  color: white;
  text-align: center;
  position: relative;
}
.page-title::before {
  content: '';
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: rgba(0, 0, 0, 0.5);
}
.page-title .container {
  position: relative;
  z-index: 1;
}
.page-title h1 {
  font-size: 3rem;
  margin-bottom: 0.5rem;
}

.content-wrapper {
  padding: 60px 0;
}

.cities-grid-full .row {
  margin-left: -10px;
  margin-right: -10px;
}

.cities-grid-full .col-sm-3 {
  padding-left: 10px;
  padding-right: 10px;
}

.city-grid-item {
  margin-bottom: 20px;
}

.city-image {
  height: 250px;
  border-radius: 8px;
  background-size: cover;
  background-position: center;
  position: relative;
  overflow: hidden;
  transition: transform 0.3s ease;
}

.city-grid-item:hover .city-image {
    transform: scale(1.03);
}

.city-overlay {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 60%);
  transition: background 0.3s ease;
}

.city-grid-item:hover .city-overlay {
    background: linear-gradient(to top, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.1) 70%);
}

.city-counter {
  position: absolute;
  top: 15px;
  right: 15px;
  background: rgba(0, 0, 0, 0.6);
  color: white;
  padding: 5px 10px;
  border-radius: 4px;
  font-size: 12px;
}

.city-name {
  position: absolute;
  bottom: 20px;
  left: 20px;
  color: white;
  font-size: 22px;
  font-weight: 600;
  z-index: 1;
}

.city-link {
  position: absolute;
  top: 0; left: 0; right: 0; bottom: 0;
  z-index: 2;
}
</style>

@endsection 