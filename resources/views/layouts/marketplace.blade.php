<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('lonika-logo2.png') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('lonika-logo2.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('lonika-logo2.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('lonika-logo2.png') }}">
    <link rel="shortcut icon" href="{{ asset('lonika-logo2.png') }}">

    <title>{{ config('app.name', 'Laravel') }} - Marketplace</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --light-bg: #f8fafc;
            --border-color: #e2e8f0;
            --shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: var(--light-bg);
            color: #1f2937;
        }

        /* Modern Navigation */
        .navbar-modern {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
            z-index: 1030; /* Higher than sticky cart summary */
        }

        .navbar-brand-modern {
            font-weight: 800;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        .nav-link-modern {
            font-weight: 500;
            color: var(--secondary-color) !important;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-link-modern:hover {
            color: var(--primary-color) !important;
            transform: translateY(-1px);
        }

        .nav-link-modern::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link-modern:hover::after {
            width: 100%;
        }
        
        /* Enhanced Navigation Icons */
        .nav-link-modern i {
            font-size: 1.1em;
            margin-right: 0.5rem;
        }
        
        .nav-link-modern .fs-5 {
            font-size: 1.25rem !important;
        }
        
        /* Profile avatar enhancement */
        .nav-link-modern .rounded-circle {
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .nav-link-modern:hover .rounded-circle {
            border-color: rgba(255, 255, 255, 0.6);
            transform: scale(1.05);
        }

        /* Buttons */
        .btn-modern-primary {
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            border: none;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 12px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
        }

        .btn-modern-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
        }

        .btn-modern-outline {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            font-weight: 600;
            padding: 10px 22px;
            border-radius: 12px;
            background: transparent;
            transition: all 0.3s ease;
        }

        .btn-modern-outline:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.3);
        }

        /* Cards */
        .card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.4s ease;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
        }

        .card-modern:hover {
            transform: translateY(-8px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-color);
        }

        .product-image-modern {
            height: 240px;
            object-fit: cover;
            width: 100%;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            transition: transform 0.4s ease;
        }

        .card-modern:hover .product-image-modern {
            transform: scale(1.05);
        }

        /* Cart Badge Enhancement */
        .cart-badge-modern {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
            border: 2px solid white;
            min-width: 20px;
        }
        
        .cart-badge-modern.bg-primary {
            background: linear-gradient(135deg, var(--primary-color), #2563eb) !important;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.4);
        }
        
        .cart-badge-modern.bg-danger {
            background: linear-gradient(135deg, #ef4444, #dc2626) !important;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4);
        }

        /* Navigation Link Icons */
        .nav-link-modern {
            color: var(--text-color) !important;
            font-weight: 500;
            border-radius: 8px;
            transition: all 0.3s ease;
            padding: 8px 12px;
            margin: 0 2px;
            position: relative;
        }

        .nav-link-modern:hover {
            color: var(--primary-color) !important;
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-1px);
        }

        .nav-link-modern i {
            font-size: 1.1em;
        }

        /* Ensure icons are visible */
        .fas, .far {
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
        }

        /* Profile dropdown enhancement */
        .dropdown-menu-modern {
            background: white;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 1rem 0;
            margin-top: 0.5rem;
            min-width: 200px;
        }

        .dropdown-item-modern {
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-color);
        }

        .dropdown-item-modern:hover {
            background: var(--background-light);
            color: var(--primary-color);
            transform: translateX(5px);
        }

        /* Hero Section */
        .hero-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
            min-height: 80vh;
            display: flex;
            align-items: center;
        }

        .hero-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* Search Bar */
        .search-bar-modern {
            border-radius: 15px;
            border: 2px solid var(--border-color);
            padding: 15px 20px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: white;
            box-shadow: var(--shadow);
            width: 100%;
            max-width: 100%;
        }

        .search-bar-modern:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Input Group Responsive */
        .input-group {
            display: flex;
            flex-wrap: wrap;
            align-items: stretch;
            width: 100%;
        }

        .input-group > .form-control {
            position: relative;
            flex: 1 1 auto;
            width: 1%;
            min-width: 0;
        }

        .input-group > .btn {
            position: relative;
            z-index: 2;
            flex-shrink: 0;
        }

        /* Badges */
        .badge-modern {
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        .badge-success-modern {
            background: linear-gradient(135deg, var(--success-color), #059669);
            color: white;
        }

        .badge-warning-modern {
            background: linear-gradient(135deg, var(--warning-color), #d97706);
            color: white;
        }

        .badge-danger-modern {
            background: linear-gradient(135deg, var(--danger-color), #dc2626);
            color: white;
        }

        .badge-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), #1d4ed8);
            color: white;
        }

        /* Dropdown */
        .dropdown-menu-modern {
            border: none;
            border-radius: 16px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--border-color);
            padding: 10px 0;
            margin-top: 10px;
        }

        .dropdown-item-modern {
            padding: 12px 20px;
            font-weight: 500;
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 0 8px;
        }

        .dropdown-item-modern:hover {
            background: var(--light-bg);
            color: var(--primary-color);
        }

        /* Notifications */
        .notification-toast {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: var(--shadow-lg);
            border-left: 4px solid var(--success-color);
            min-width: 300px;
            max-width: 400px;
            transform: translateX(100%);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .notification-toast.show {
            transform: translateX(0);
        }

        .notification-toast.alert-success {
            border-left-color: var(--success-color);
        }

        .notification-toast.alert-danger {
            border-left-color: var(--danger-color);
        }

        .notification-toast.alert-info {
            border-left-color: var(--primary-color);
        }

        .notification-toast.alert-warning {
            border-left-color: var(--warning-color);
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease forwards;
        }

        /* Smooth transitions */
        * {
            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Fix overflow */
        body {
            overflow-x: hidden;
        }

        /* Stats cards */
        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            text-align: center;
            border: 1px solid var(--border-color);
            box-shadow: var(--shadow);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        /* Product Cards Enhancement */
        .product-image-modern {
            width: 100%;
            height: 220px;
            object-fit: cover;
            transition: transform 0.3s ease;
            /* Image optimization */
            image-rendering: optimizeQuality;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            will-change: transform;
        }

        .card-modern:hover .product-image-modern {
            transform: scale(1.05);
        }

        /* Image loading optimizations */
        .product-image-modern[loading="lazy"] {
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .product-image-modern[loading="lazy"].loaded {
            opacity: 1;
        }

        /* Intersection Observer fallback */
        .no-js .product-image-modern[loading="lazy"] {
            opacity: 1;
        }

        /* Image container optimization */
        .product-image-container {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Loading states */
        .image-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, transparent 37%, #f0f0f0 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s ease-in-out infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: 100% 50%;
            }
            100% {
                background-position: -100% 50%;
            }
        }

        /* Responsive image container */
        @media (min-width: 768px) {
            .product-image-modern {
                height: 240px;
            }
        }

        @media (min-width: 992px) {
            .product-image-modern {
                height: 260px;
            }
        }

        /* Performance optimizations */
        .card-modern {
            contain: layout style paint;
        }

        .product-image-modern {
            contain: layout style paint;
        }

        /* Enhanced Badges */
        .badge-warning-modern {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(245, 158, 11, 0.3);
        }

        .badge-danger-modern {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .badge-success-modern {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
        }

        .badge-primary-modern {
            background: linear-gradient(135deg, var(--primary-color), #2563eb);
            color: white;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
        }

        /* Stats Cards */
        .stats-card {
            text-align: center;
            padding: 1rem;
        }

        /* Animation Classes */
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Filter Buttons Enhancement */
        .btn-check:checked + .btn-outline-secondary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-check:checked + .btn-outline-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        /* Category Cards Animation */
        .card-modern[data-category] {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .card-modern[data-category]:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }

        /* Hero Section Enhancement */
        .hero-modern {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            min-height: 60vh;
            display: flex;
            align-items: center;
            position: relative;
            overflow: hidden;
        }

        .hero-modern::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 20"><defs><radialGradient id="a" cx="50" cy="50" r="50"><stop offset="0" stop-color="white" stop-opacity="0.1"/><stop offset="1" stop-color="white" stop-opacity="0"/></radialGradient></defs><circle cx="50" cy="10" r="10" fill="url(%23a)"/></svg>') repeat;
            opacity: 0.1;
        }

        /* Simplified Product Cards */
        .product-card-simple {
            transition: all 0.3s ease;
            border-radius: 12px;
            overflow: hidden;
        }

        .product-card-simple:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .product-image-simple {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card-simple:hover .product-image-simple {
            transform: scale(1.05);
        }

        /* Category Cards Simplified */
        .category-card-simple {
            transition: all 0.3s ease;
            border-radius: 12px;
            cursor: pointer;
        }

        .category-card-simple:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Wishlist Button Enhancement */
        .wishlist-btn {
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .wishlist-btn:hover {
            transform: scale(1.1);
        }

        .wishlist-btn.btn-danger {
            background-color: #dc3545 !important;
            border-color: #dc3545 !important;
            color: white !important;
        }

        /* Navigation Icon Enhancement */
        .nav-link-modern .fs-5 {
            transition: all 0.3s ease;
        }

        .nav-link-modern:hover .fs-5 {
            transform: scale(1.1);
        }

        /* Button Improvements */
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 8px;
        }

        /* Search Bar Hero */
        .hero-modern .search-bar-modern {
            border-radius: 50px 0 0 50px;
            border: none;
            padding: 15px 20px;
        }

        .hero-modern .btn {
            border-radius: 0 50px 50px 0;
            padding: 15px 25px;
        }

        /* Stats Enhancement */
        .bg-light {
            background-color: #f8f9fa !important;
        }

        /* Enhanced Responsive Design */
        
        /* Mobile First Approach */
        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .hero-modern {
                min-height: 40vh;
                padding: 2rem 0;
            }
            
            .display-4 {
                font-size: 1.8rem;
                line-height: 1.3;
            }
            
            .display-5 {
                font-size: 1.5rem;
            }
            
            .lead {
                font-size: 0.95rem;
            }
            
            .search-bar-modern {
                font-size: 14px;
                padding: 12px 16px;
            }
            
            .btn-modern-primary {
                padding: 12px 20px;
                font-size: 14px;
            }
            
            .card-modern {
                margin-bottom: 1rem;
            }
            
            .product-image-simple {
                height: 180px;
            }
            
            .stats-card {
                padding: 1rem;
                margin-bottom: 1rem;
            }
            
            .stats-card h3 {
                font-size: 1.25rem;
            }
            
            .nav-icon-label {
                display: none !important;
            }
            
            .navbar-brand-modern {
                font-size: 1.2rem;
            }
        }

        /* Tablet Portrait */
        @media (min-width: 577px) and (max-width: 768px) {
            .hero-modern {
                min-height: 50vh;
                padding: 3rem 0;
            }
            
            .display-4 {
                font-size: 2.2rem;
            }
            
            .display-5 {
                font-size: 1.8rem;
            }
            
            .product-image-simple {
                height: 200px;
            }
            
            .search-bar-modern {
                padding: 14px 18px;
            }
            
            .btn-group {
                display: flex;
                flex-wrap: wrap;
                gap: 0.5rem;
            }
            
            .btn-group .btn {
                flex: 1;
                min-width: auto;
            }
            
            .stats-card h3 {
                font-size: 1.5rem;
            }
            
            .container {
                max-width: 720px;
            }
        }

        /* Tablet Landscape & Small Laptop */
        @media (min-width: 769px) and (max-width: 991px) {
            .hero-modern {
                min-height: 55vh;
                padding: 4rem 0;
            }
            
            .display-4 {
                font-size: 2.5rem;
            }
            
            .display-5 {
                font-size: 2rem;
            }
            
            .product-image-simple {
                height: 220px;
            }
            
            .search-bar-modern {
                padding: 15px 20px;
                font-size: 15px;
            }
            
            .container {
                max-width: 960px;
            }
            
            .col-lg-3 {
                flex: 0 0 auto;
                width: 50%;
            }
            
            .col-md-6 {
                width: 50%;
            }
        }

        /* Desktop & Large Laptop */
        @media (min-width: 992px) and (max-width: 1199px) {
            .hero-modern {
                min-height: 60vh;
                padding: 5rem 0;
            }
            
            .display-4 {
                font-size: 3rem;
            }
            
            .display-5 {
                font-size: 2.25rem;
            }
            
            .product-image-simple {
                height: 240px;
            }
            
            .container {
                max-width: 1140px;
            }
            
            .col-lg-3 {
                flex: 0 0 auto;
                width: 25%;
                max-width: 25%;
            }
            
            .col-lg-4 {
                width: 33.333333%;
            }
            
            .col-lg-6 {
                width: 50%;
            }
            
            .search-bar-modern {
                max-width: 500px;
            }
        }

        /* Large Desktop & 4K */
        @media (min-width: 1200px) {
            .hero-modern {
                min-height: 65vh;
                padding: 6rem 0;
            }
            
            .display-4 {
                font-size: 3.5rem;
            }
            
            .display-5 {
                font-size: 2.5rem;
            }
            
            .product-image-simple {
                height: 260px;
            }
            
            .container {
                max-width: 1200px !important;
            }
            
            .search-bar-modern {
                max-width: 600px;
            }
            
            .btn-modern-primary {
                padding: 16px 32px;
                font-size: 16px;
            }
            
            .card-modern {
                border-radius: 24px;
            }
            
            .stats-card {
                padding: 2rem;
            }
        }

        /* Ultra Wide Screens - Limited container width */
        @media (min-width: 1400px) {
            .container {
                max-width: 1200px !important;
            }
            
            .hero-modern {
                min-height: 70vh;
            }
            
            .display-4 {
                font-size: 4rem;
            }
            
            .product-image-simple {
                height: 280px;
            }
        }

        /* Navigation Responsive Fixes */
        @media (max-width: 991px) {
            .navbar-nav {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 10px;
                padding: 1rem;
                margin-top: 1rem;
                backdrop-filter: blur(10px);
            }
            
            .nav-icon-container {
                margin: 0 8px;
            }
            
            .nav-icon-link {
                padding: 6px 8px;
            }
        }

        /* Search Bar Navigation Responsive */
        @media (max-width: 1199px) {
            .navbar .search-bar-modern {
                max-width: 300px;
            }
        }

        @media (max-width: 991px) {
            .navbar .search-bar-modern {
                max-width: 250px;
                font-size: 13px;
                padding: 6px 12px;
            }
        }

        /* Footer Responsive */
        @media (max-width: 768px) {
            footer .col-md-4 {
                text-align: center;
                margin-bottom: 2rem;
            }
            
            footer h5 {
                font-size: 1.1rem;
            }
        }

        /* Card Grid Responsive */
        @media (min-width: 576px) {
            .row-cols-sm-2 > * {
                flex: 0 0 auto;
                width: 50%;
            }
        }

        @media (min-width: 768px) {
            .row-cols-md-3 > * {
                flex: 0 0 auto;
                width: 33.333333%;
            }
        }

        @media (min-width: 992px) {
            .row-cols-lg-4 > * {
                flex: 0 0 auto;
                width: 25%;
            }
        }

        @media (min-width: 1200px) {
            .row-cols-xl-5 > * {
                flex: 0 0 auto;
                width: 20%;
            }
        }

        /* Content Spacing Responsive */
        .section-spacing {
            padding: 2rem 0;
        }

        @media (min-width: 768px) {
            .section-spacing {
                padding: 3rem 0;
            }
        }

        @media (min-width: 992px) {
            .section-spacing {
                padding: 4rem 0;
            }
        }

        @media (min-width: 1200px) {
            .section-spacing {
                padding: 5rem 0;
            }
        }

        /* Enhanced Typography */
        .display-4, .display-5, .display-6 {
            font-weight: 700;
            line-height: 1.2;
        }

        /* Product Grid Animation */
        .product-item {
            transition: all 0.3s ease;
        }

        /* Force icon visibility */
        .fas, .far, .fab {
            font-family: "Font Awesome 6 Free", "Font Awesome 5 Free", "FontAwesome" !important;
            font-weight: 900 !important;
            display: inline-block !important;
        }

        .far {
            font-weight: 400 !important;
        }

        /* Icon fallbacks using Unicode */
        .fa-heart::before { content: "\f004"; }
        .fa-shopping-cart::before { content: "\f07a"; }
        .fa-user::before { content: "\f007"; }
        .fa-clipboard-list::before { content: "\f46d"; }
        .fa-store::before { content: "\f54e"; }
        .fa-search::before { content: "\f002"; }
        .fa-sign-out-alt::before { content: "\f2f5"; }
        .fa-sign-in-alt::before { content: "\f2f6"; }
        .fa-user-plus::before { content: "\f234"; }

        /* Ensure navigation items are visible */
        .navbar-nav .nav-item {
            position: relative;
        }

        .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            padding: 0.5rem 1rem !important;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
        }

        /* Force navbar to always show icons */
        .navbar-nav {
            display: flex !important;
            flex-direction: row !important;
        }

        .navbar-collapse {
            display: flex !important;
        }

        /* Ensure icons are visible on all screen sizes */
        @media (max-width: 991px) {
            .navbar-nav {
                flex-direction: row !important;
                justify-content: center;
                width: 100%;
                margin-top: 1rem;
            }
            
            .navbar-nav .nav-item {
                margin: 0 10px;
            }
            
            .navbar-toggler {
                display: none !important;
            }
        }

        /* Badge positioning fix */
        .position-relative .cart-badge-modern {
            position: absolute !important;
            top: -5px !important;
            right: -5px !important;
            z-index: 10;
        }

        /* Modern Navigation Icons */
        .nav-icon-container {
            position: relative;
            text-align: center;
        }

        .nav-icon-link {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-decoration: none;
            color: #6b7280;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 8px;
        }

        .nav-icon-link:hover {
            color: #3b82f6;
            background-color: rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
            text-decoration: none;
        }

        .nav-icon-link i {
            font-size: 18px;
            margin-bottom: 2px;
        }

        .nav-icon-label {
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
        }

        .nav-badge {
            position: absolute;
            top: 2px;
            right: 8px;
            background: #ef4444;
            color: white;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            font-size: 9px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px solid white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .nav-badge.bg-primary {
            background: #3b82f6 !important;
        }

        .nav-badge.bg-danger {
            background: #ef4444 !important;
        }

        /* Brand enhancement */
        .navbar-brand-modern {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary-color) !important;
            text-decoration: none;
        }

        /* Search bar in navigation */
        .navbar .search-bar-modern {
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            padding: 8px 16px;
            font-size: 14px;
        }

        .navbar .btn-modern-primary {
            border-radius: 20px;
            padding: 8px 16px;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .nav-icon-label {
                display: none;
            }
            
            .nav-icon-link {
                padding: 6px 8px;
            }
        }

        /* Dropdown menu visibility */
        .dropdown-menu {
            display: none;
        }

        .dropdown-menu.show {
            display: block;
        }

        /* Mobile navigation */
        @media (max-width: 991.98px) {
            .navbar-nav {
                background: rgba(0, 0, 0, 0.1);
                border-radius: 10px;
                padding: 1rem;
                margin-top: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-modern sticky-top">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand-modern" href="{{ route('peminjaman.index') }}">
                <img src="{{ asset('images/lonika-logo.png') }}" 
                     alt="Lonika Logo" 
                     class="d-inline-block align-text-top me-2" 
                     style="height: 40px; width: auto;"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='inline';">
                <span class="d-none"><i class="fas fa-store me-2"></i><strong>Lonika</strong></span>
            </a>
            
            <!-- Search Bar - Center -->
            <div class="mx-auto d-none d-md-block flex-grow-1" style="max-width: 500px;">
                <div class="input-group">
                    <input type="text" 
                           class="form-control search-bar-modern" 
                           placeholder="Cari produk..." 
                           id="searchInput" 
                           value="{{ request('search') }}"
                           autocomplete="off"
                           spellcheck="false"
                           autocorrect="off"
                           autocapitalize="off"
                           data-lpignore="true"
                           data-form-type="other">
                    <button class="btn btn-modern-primary" type="button" onclick="performNavSearch()">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Search Toggle -->
            <button class="btn btn-outline-primary d-md-none me-2" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch">
                <i class="fas fa-search"></i>
            </button>
            
            <!-- Navigation Icons -->
            <div class="d-flex align-items-center">
                @auth
                    <!-- Favorites -->
                    <div class="nav-icon-container me-3">
                        <a href="{{ route('peminjaman.wishlist') }}" class="nav-icon-link" title="Favorit">
                            <i class="fas fa-heart text-danger fs-5"></i>
                            <span class="nav-icon-label d-none d-sm-block">Favorit</span>
                            <span class="nav-badge bg-danger wishlist-badge d-none">0</span>
                        </a>
                    </div>
                    
                    <!-- Cart -->
                    <div class="nav-icon-container me-3">
                        <a href="{{ route('peminjaman.cart') }}" class="nav-icon-link" title="Keranjang">
                            <i class="fas fa-shopping-cart text-primary fs-5"></i>
                            <span class="nav-icon-label d-none d-sm-block">Keranjang</span>
                            <span class="nav-badge bg-primary cart-badge">0</span>
                        </a>
                    </div>
                    
                    <!-- Orders -->
                    <div class="nav-icon-container me-3">
                        <a href="{{ route('peminjaman.orders') }}" class="nav-icon-link" title="Pesanan">
                            <i class="fas fa-clipboard-list text-success fs-5"></i>
                            <span class="nav-icon-label d-none d-sm-block">Pesanan</span>
                        </a>
                    </div>
                    
                    <!-- Profile -->
                    <div class="nav-icon-container">
                        <div class="dropdown">
                            <a href="#" class="nav-icon-link dropdown-toggle" data-bs-toggle="dropdown" title="Profil">
                                <i class="fas fa-user text-info fs-5"></i>
                                <span class="nav-icon-label d-none d-sm-block">{{ Str::limit(auth()->user()->name, 8) }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profil
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('custom.logout') }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                @else
                    <div class="nav-icon-container">
                        <a href="{{ route('login') }}" class="nav-icon-link" title="Login">
                            <i class="fas fa-sign-in-alt text-success fs-5"></i>
                            <span class="nav-icon-label d-none d-sm-block">Login</span>
                        </a>
                    </div>
                @endauth
            </div>
        </div>
        
        <!-- Mobile Search Collapse -->
        <div class="collapse d-md-none" id="mobileSearch">
            <div class="container py-3">
                <div class="input-group">
                    <input type="text" 
                           class="form-control search-bar-modern" 
                           placeholder="Cari produk..." 
                           id="mobileSearchInput" 
                           value="{{ request('search') }}"
                           autocomplete="off"
                           spellcheck="false"
                           autocorrect="off"
                           autocapitalize="off"
                           data-lpignore="true"
                           data-form-type="other">
                    <button class="btn btn-modern-primary" type="button" onclick="performMobileSearch()">
                        <i class="fas fa-search text-white"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Lonika Store</h5>
                    <p class="text-light">Platform peminjaman </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('peminjaman.index') }}" class="text-light text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="{{ route('peminjaman.orders') }}" class="text-light text-decoration-none">Pesanan Saya</a></li>
                        <li class="mb-2"><a href="{{ route('peminjaman.cart') }}" class="text-light text-decoration-none">Keranjang</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none">Bantuan</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Contact Info</h5>
                    <div class="text-light">
                        <p class="mb-2"><i class="fas fa-envelope me-2"></i>info@lonikastore.com</p>
                        <p class="mb-2"><i class="fas fa-phone me-2"></i>+62 123 456 789</p>
                        <p class="mb-2"><i class="fas fa-map-marker-alt me-2"></i>Jakarta, Indonesia</p>
                        <p class="mb-0"><i class="fas fa-clock me-2"></i>24/7 Customer Service</p>
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0">&copy; {{ date('Y') }} Lonika Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // === BROWSER EXTENSION ERROR SUPPRESSION ===
        // Override console methods to filter out extension errors
        (function() {
            const originalError = console.error;
            const originalWarn = console.warn;
            
            console.error = function(...args) {
                const message = args.join(' ');
                if (!message.includes('message port') && 
                    !message.includes('content.bundle') && 
                    !message.includes('extension') &&
                    !message.includes('runtime.lastError') &&
                    !message.includes('closed before a response was received')) {
                    originalError.apply(console, args);
                }
            };
            
            console.warn = function(...args) {
                const message = args.join(' ');
                if (!message.includes('extension') && 
                    !message.includes('content.js') &&
                    !message.includes('message port')) {
                    originalWarn.apply(console, args);
                }
            };
        })();

        // Global variables
        let isUpdatingCart = false;
        
        // Cart and wishlist functions (optimized)
        function updateCartCount() {
            if (isUpdatingCart) return;
            isUpdatingCart = true;
            
            fetch('/peminjaman/cart/count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const cartBadge = document.querySelector('.cart-badge');
                if (cartBadge && data.success) {
                    cartBadge.textContent = data.cart_count;
                    cartBadge.style.display = data.cart_count > 0 ? 'flex' : 'none';
                    if (data.cart_count > 0) {
                        cartBadge.classList.remove('d-none');
                    } else {
                        cartBadge.classList.add('d-none');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating cart count:', error);
            })
            .finally(() => {
                isUpdatingCart = false;
            });
        }

        function updateWishlistCount() {
            fetch('/peminjaman/wishlist/count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            })
            .then(response => response.json())
            .then(data => {
                const wishlistBadge = document.querySelector('.wishlist-badge');
                if (wishlistBadge && data.success) {
                    wishlistBadge.textContent = data.wishlist_count;
                    if (data.wishlist_count > 0) {
                        wishlistBadge.style.display = 'flex';
                        wishlistBadge.classList.remove('d-none');
                    } else {
                        wishlistBadge.style.display = 'none';
                        wishlistBadge.classList.add('d-none');
                    }
                }
            })
            .catch(error => {
                console.error('Error updating wishlist count:', error);
            });
        }

        function toggleWishlist(barangId, event) {
            if (event) {
                event.preventDefault();
                event.stopPropagation();
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                showNotification('Token keamanan tidak ditemukan', 'error');
                return;
            }
            
            // Check if item is already in wishlist (from session)
            fetch('/peminjaman/wishlist/count', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // For now, we'll assume it's an add operation
                // In a real app, you'd check if the item is already in wishlist
                addToWishlist(barangId, event);
            })
            .catch(error => {
                console.error('Error checking wishlist:', error);
                addToWishlist(barangId, event);
            });
        }
        
        function addToWishlist(barangId, event) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            
            fetch('/peminjaman/wishlist/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    barang_id: parseInt(barangId)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const button = event ? event.target.closest('button') : null;
                    if (button) {
                        const icon = button.querySelector('i');
                        if (icon) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                        }
                        button.classList.remove('btn-light');
                        button.classList.add('btn-danger');
                    }
                    
                    // Update wishlist count
                    updateWishlistCount();
                    
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message || 'Gagal menambahkan ke wishlist', 'warning');
                }
            })
            .catch(error => {
                console.error('Error adding to wishlist:', error);
                showNotification('Terjadi kesalahan saat menambahkan ke wishlist', 'error');
            });
        }

        function addToCart(barangId, quantity = 1) {
            console.log('Adding to cart:', barangId, quantity);
            
            // Validate inputs quickly
            if (!barangId || barangId <= 0) {
                showNotification('ID barang tidak valid', 'error');
                return;
            }
            
            if (!quantity || quantity <= 0) {
                quantity = 1;
            }
            
            // Check CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                showNotification('Token keamanan tidak ditemukan', 'error');
                return;
            }
            
            // Optimized AJAX request
            fetch('/peminjaman/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    barang_id: parseInt(barangId),
                    quantity: parseInt(quantity)
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update cart badge immediately
                    const cartBadge = document.querySelector('.cart-badge');
                    if (cartBadge && data.cart_count !== undefined) {
                        cartBadge.textContent = data.cart_count;
                        cartBadge.style.display = 'flex';
                        cartBadge.classList.remove('d-none');
                    }
                    
                    // Show success notification
                    showNotification(data.message || 'Produk berhasil ditambahkan ke keranjang', 'success');
                } else {
                    showNotification(data.message || 'Gagal menambahkan ke keranjang', 'error');
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
            });
        }

        // Make function globally accessible
        window.addToCart = addToCart;

        function showNotification(message, type = 'info', duration = 4000) {
            // Remove existing notifications of the same type
            const existing = document.querySelectorAll('.notification-toast');
            existing.forEach(notification => {
                if (notification.classList.contains(`alert-${type === 'error' ? 'danger' : type}`)) {
                    notification.remove();
                }
            });
            
            // Create new notification
            const notification = document.createElement('div');
            notification.className = `alert alert-${type === 'error' ? 'danger' : type} notification-toast position-fixed`;
            notification.style.cssText = `
                top: 20px; 
                right: 20px; 
                z-index: 9999; 
                min-width: 300px; 
                max-width: 400px;
                opacity: 0;
                transform: translateX(100%);
                transition: all 0.3s ease;
            `;
            
            const iconMap = {
                success: 'check-circle',
                error: 'exclamation-circle',
                info: 'info-circle',
                warning: 'exclamation-triangle'
            };
            
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="fas fa-${iconMap[type] || 'info-circle'} me-2"></i>
                    <span class="flex-grow-1">${message}</span>
                    <button type="button" class="btn-close ms-2" onclick="this.closest('.notification-toast').remove()"></button>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.style.opacity = '1';
                notification.style.transform = 'translateX(0)';
            }, 10);
            
            // Auto remove
            if (duration > 0) {
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.style.opacity = '0';
                        notification.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            if (notification.parentElement) {
                                notification.remove();
                            }
                        }, 300);
                    }
                }, duration);
            }
            
            return notification;
        }

        function showWishlist() {
            window.location.href = "{{ route('peminjaman.wishlist') }}";
        }
        
        function performNavSearch() {
            const searchInput = document.getElementById('searchInput');
            const searchTerm = searchInput ? searchInput.value.trim() : '';
            
            if (searchTerm) {
                window.location.href = `{{ route('peminjaman.index') }}?search=${encodeURIComponent(searchTerm)}#productGrid`;
            } else {
                window.location.href = `{{ route('peminjaman.index') }}#productGrid`;
            }
        }

        function performMobileSearch() {
            const searchInput = document.getElementById('mobileSearchInput');
            const searchTerm = searchInput ? searchInput.value.trim() : '';
            
            if (searchTerm) {
                window.location.href = `{{ route('peminjaman.index') }}?search=${encodeURIComponent(searchTerm)}#productGrid`;
            } else {
                window.location.href = `{{ route('peminjaman.index') }}#productGrid`;
            }
        }

        // Make functions globally accessible
        window.addToCart = addToCart;
        window.updateCartCount = updateCartCount;
        window.updateWishlistCount = updateWishlistCount;
        window.toggleWishlist = toggleWishlist;
        window.addToWishlist = addToWishlist;
        window.showWishlist = showWishlist;
        window.performNavSearch = performNavSearch;
        window.performMobileSearch = performMobileSearch;
        window.showNotification = showNotification;

        // Debounce function to prevent multiple rapid calls
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Debounced version of updateCartCount
        const debouncedUpdateCartCount = debounce(updateCartCount, 300);

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Page loaded, initializing...');
            
            // === IMAGE OPTIMIZATION ===
            initImageOptimization();
            
            // Initialize counts
            updateCartCount();
            updateWishlistCount();
            
            // Search functionality
            const searchInput = document.getElementById('searchInput');
            if (searchInput) {
                searchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performNavSearch();
                    }
                });
            }
            
            // Mobile search functionality
            const mobileSearchInput = document.getElementById('mobileSearchInput');
            if (mobileSearchInput) {
                mobileSearchInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        performMobileSearch();
                    }
                });
            }
            
            // Periodic cart count update (every 60 seconds - reduced frequency)
            setInterval(debouncedUpdateCartCount, 60000);
        });

        // Image optimization functions
        function initImageOptimization() {
            // Progressive loading for critical images
            preloadCriticalImages();
            
            // Setup lazy loading observer
            if ('IntersectionObserver' in window) {
                setupLazyLoading();
            }
            
            // Optimize existing images
            optimizeLoadedImages();
        }

        function preloadCriticalImages() {
            // Preload first 4 visible images
            const criticalImages = document.querySelectorAll('.product-image-modern, .product-image-simple');
            const preloadCount = Math.min(4, criticalImages.length);
            
            for (let i = 0; i < preloadCount; i++) {
                const img = criticalImages[i];
                if (img && img.src && !img.complete) {
                    const preloadImg = new Image();
                    preloadImg.src = img.src;
                }
            }
        }

        function setupLazyLoading() {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        
                        // Show loading state
                        const container = img.closest('.product-image-container') || img.parentElement;
                        if (container) {
                            container.classList.add('image-loading');
                        }
                        
                        // Load image
                        img.addEventListener('load', function() {
                            this.classList.add('loaded');
                            if (container) {
                                container.classList.remove('image-loading');
                            }
                        });
                        
                        img.addEventListener('error', function() {
                            if (container) {
                                container.classList.remove('image-loading');
                                container.innerHTML = `
                                    <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                        <div class="text-center">
                                            <i class="fas fa-image fa-2x mb-2"></i>
                                            <div class="small">Gambar tidak tersedia</div>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                        
                        observer.unobserve(img);
                    }
                });
            }, {
                rootMargin: '50px 0px',
                threshold: 0.1
            });

            // Observe all lazy images
            document.querySelectorAll('img[loading="lazy"]').forEach(img => {
                imageObserver.observe(img);
            });
        }

        function optimizeLoadedImages() {
            const images = document.querySelectorAll('.product-image-modern, .product-image-simple');
            
            images.forEach(img => {
                if (img.complete && img.naturalHeight !== 0) {
                    img.classList.add('loaded');
                } else {
                    img.addEventListener('load', function() {
                        this.classList.add('loaded');
                    });
                    
                    img.addEventListener('error', function() {
                        const container = this.closest('.product-image-container') || this.parentElement;
                        if (container) {
                            container.innerHTML = `
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <div class="text-center">
                                        <i class="fas fa-image fa-2x mb-2"></i>
                                        <div class="small">Gambar tidak tersedia</div>
                                    </div>
                                </div>
                            `;
                        }
                    });
                }
            });
        }

        // Handle page visibility change
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                // Page became visible, update cart count
                setTimeout(updateCartCount, 500);
            }
        });

        // Global error handler for browser extension errors
        window.addEventListener('error', function(event) {
            // Ignore browser extension errors
            if (event.filename.includes('extension') || 
                event.filename.includes('content') || 
                event.message.includes('message port')) {
                console.log('Browser extension error ignored:', event.message);
                event.preventDefault();
                return false;
            }
        });

        // Handle unhandled promise rejections from extensions
        window.addEventListener('unhandledrejection', function(event) {
            if (event.reason && typeof event.reason === 'object' && 
                (event.reason.message || '').includes('message port')) {
                console.log('Browser extension promise rejection ignored');
                event.preventDefault();
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
