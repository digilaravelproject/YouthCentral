<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="UTF-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
      <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/img/favicon.png') }}">
  <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
  
    <title>@yield('title', config('app.name', 'Youth Central'))</title>

    <!-- Public CSS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa" rel="stylesheet">
    
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet" />

    
    <link href="{{ asset('assets_public/lib/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('assets_public/css/style.css') }}" rel="stylesheet">

    <style>
        /* Basic body styling for minimal layout */
        body {
            background-color: #f4f6f9; /* Lighter grey for background */
            font-family: 'Roboto', sans-serif;
        }
        .minimal-content-wrapper {
             display: flex;
             flex-direction: column;
             align-items: center; /* Center children horizontally */
             justify-content: center; /* Center children vertically */
             min-height: 100vh;
             padding: 1rem; /* Add padding for smaller screens */
        }
        /* Style for the main card in minimal layout */
        .minimal-card {
            background-color: #ffffff;
            border-radius: 12px; /* More rounded corners */
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1); /* Material-like shadow */
            width: 100%; /* Take full width of its container */
            max-width: 550px; /* Max width for the card */
            overflow: hidden; /* Ensure header respects border radius */
        }
        /* Back button style */
        .back-link-minimal {
            position: absolute;
            top: 15px;
            left: 15px;
            color: #6c757d;
            font-size: 1.5rem;
            transition: color 0.2s ease;
        }
        .back-link-minimal:hover {
            color: #343a40;
        }
        
    </style>

    @stack('styles')
</head>
<body>
    {{-- Back Button --}}
    <a href="{{ url()->previous() }}" class="back-link-minimal" title="Go Back">
        <i class="fas fa-arrow-circle-left"></i>
    </a>
    
    <div class="minimal-content-wrapper">
        @yield('content')
    </div>

    <!-- Minimal JS -->
    <script src="{{ asset('assets_public/lib/jquery.min.js') }}"></script>
    <script src="{{ asset('assets_public/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success'))
                toastr.success("{{ session('success') }}");
            @endif

            @if (session('error'))
                toastr.error("{{ session('error') }}");
            @endif

            @if (session('info'))
                toastr.info("{{ session('info') }}");
            @endif

            @if (session('warning'))
                toastr.warning("{{ session('warning') }}");
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html> 
 
 
 
 
 