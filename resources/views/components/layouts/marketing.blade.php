@props([
    'title' => 'VelocityMarkets',
    'description' => 'The modern crypto trading platform.',
])
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>{{ $title }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="{{ $description }}">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Template CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/bayya/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bayya/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bayya/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bayya/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bayya/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/bayya/css/skins/orange.css') }}">

    <!-- Template JS Files -->
    <script src="{{ asset('assets/bayya/js/modernizr.js') }}"></script>

    <style>
        .brand-logo {
            display: block;
            height: 32px;
            width: auto;
        }
        .mobile-drawer-logo .brand-logo {
            height: 36px;
        }

        /* Single-row header on desktop: logo, nav links and sign-in/register together.
           On mobile the header scrolls normally (not fixed) and everything stacks. */
        .header-row-inline .unstyled.user {
            margin: 0;
            padding: 0;
        }

        /* Every marketing page: header starts transparent, overlaid on the
           page's hero/banner image, and only becomes a solid fixed bar once
           the page is scrolled. */
        .header {
            transition: background-color .35s ease, box-shadow .35s ease;
        }
        body.hero-header .header {
            background: transparent;
            box-shadow: none;
        }
        body.hero-header .header.header-scrolled {
            background: #1d1d1d;
            box-shadow: 0 4px 14px rgba(0, 0, 0, .35);
        }
        body.hero-header .wrapper {
            padding-top: 0;
        }
        /* Pages whose first section is the standard title banner (About,
           Services, Pricing, FAQ, Terms, Contact, 404) or the maintenance
           CTA (503) need extra top clearance so their heading doesn't sit
           under the transparent header the way home's hero already does. */
        .mobile-drawer + .banner-area .banner-overlay {
            padding-top: 160px;
        }
        .mobile-drawer + .call-action-all .call-action-all-overlay {
            padding-top: 160px;
        }
        @media (max-width: 767px) {
            .mobile-drawer + .banner-area .banner-overlay,
            .mobile-drawer + .call-action-all .call-action-all-overlay {
                padding-top: 140px;
            }
        }
        .navbar-toggle {
            display: none;
        }
        .navbar-collapse.collapse {
            display: block;
            height: auto;
            overflow: visible;
            padding: 0;
        }

        @media (min-width: 768px) {
            .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }
            .wrapper {
                padding-top: 82px;
            }
            .header-row-inline {
                display: flex;
                align-items: center;
            }
            .header-row-inline .navbar-collapse {
                display: flex;
                align-items: center;
            }
            .header-nav-inline {
                display: flex;
                align-items: center;
                justify-content: center;
                flex: 1;
                margin: 0;
            }
            .header-row-inline .unstyled.user {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
        }

        @media (max-width: 767px) {
            /* style.css pushes .header down 63px to make room for the original
               template's separate fixed mobile nav bar, which we removed when
               merging nav into the header + off-canvas drawer. Undo that gap. */
            .header {
                margin-top: 0;
            }
            /* Overlay the mobile hero/banner the same way as desktop —
               fixed + transparent until scrolled. */
            body.hero-header .header {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                z-index: 1000;
            }
            .header-row-inline {
                display: flex;
                align-items: center;
                justify-content: flex-start;
                padding: 20px 0;
            }
            .main-logo {
                padding: 10px 0 !important;
                width: auto;
                flex: 0 0 auto;
            }
            .navbar-collapse {
                display: none !important;
            }
            /* This Bootstrap column (.col-xs-6) still reserves 50% width even
               with its contents hidden, which threw off layout below —
               drop the whole column, not just the <ul> inside it. */
            .header-row-inline > .col-xs-6.col-md-3 {
                display: none !important;
            }
            /* Shrink the nav column to just its toggle button, then push it
               flush to the right edge with auto margin — more reliable than
               justify-content:space-between, which unexpectedly left a gap. */
            .header-row-inline > .col-xs-12.col-md-7 {
                width: auto;
                flex: 0 0 auto;
                padding-left: 0;
                margin-left: auto;
            }
            .navbar-toggle {
                display: block;
                float: none;
                margin: 0;
            }
        }

        /* Ghost sign-in button */
        ul.user li.sign-in a.btn-primary {
            border: 1px solid #fd961a;
        }
        ul.user li.sign-in a.btn-primary:hover {
            background: #fd961a;
            color: #fff;
        }

        /* Investment plan cards */
        .investment-plans-row {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 24px;
            margin-top: 20px;
        }
        .investment-plan-card {
            flex: 0 0 230px;
            background: #1a1a1a;
            border: 1px solid #222;
            padding: 35px 20px;
            text-align: center;
        }
        .investment-plan-name {
            display: block;
            color: #fd961a;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 14px;
        }
        .investment-plan-price {
            color: #fff;
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 24px;
        }
        .investment-plan-card .btn {
            width: 100%;
        }
        @media (max-width: 767px) {
            .investment-plan-card {
                flex: 0 0 calc(50% - 12px);
                padding: 25px 12px;
            }
            .investment-plan-price {
                font-size: 22px;
            }
        }

        /* Full-width CEO quote CTA (no chart column) */
        .cta-quote-full blockquote {
            max-width: 820px;
            margin: 0 auto;
            text-align: center;
        }
        .cta-quote-full blockquote footer {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Looping testimonials marquee */
        .testimonials-section {
            padding: 90px 0 60px;
        }
        .testimonials-track-wrap {
            overflow: hidden;
            margin-top: 30px;
        }
        .testimonials-track {
            display: flex;
            width: max-content;
            animation: testimonials-scroll 45s linear infinite;
        }
        .testimonials-track:hover {
            animation-play-state: paused;
        }
        @keyframes testimonials-scroll {
            0%   { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .testimonial-card {
            flex: 0 0 340px;
            margin: 0 15px;
            background: #1a1a1a;
            border: 1px solid #222;
            padding: 30px;
        }
        .testimonial-card p {
            color: #ccc;
            font-style: italic;
            line-height: 26px;
            margin-bottom: 20px;
            min-height: 130px;
        }
        .testimonial-card footer {
            border-top: 1px solid #222;
            padding-top: 15px;
        }
        .testimonial-card footer strong {
            display: block;
            color: #fff;
            font-size: 13px;
            text-transform: uppercase;
        }
        .testimonial-card footer span {
            display: block;
            color: #fd961a;
            font-size: 12px;
            margin-top: 3px;
        }

        .mobile-drawer-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.7); z-index: 1999; opacity: 0; visibility: hidden; transition: opacity .3s ease; }
        .mobile-drawer-overlay.open { opacity: 1; visibility: visible; }
        .mobile-drawer { position: fixed; top: 0; left: 0; height: 100%; width: 85%; max-width: 320px; background: #111; z-index: 2000; padding: 30px 25px; overflow-y: auto; transform: translateX(-100%); transition: transform .3s ease; }
        .mobile-drawer.open { transform: translateX(0); }
        .mobile-drawer-close { position: absolute; top: 15px; right: 15px; background: transparent; border: 0; color: #fff; font-size: 28px; line-height: 1; cursor: pointer; }
        .mobile-drawer-logo { margin-bottom: 30px; }
        .mobile-drawer-nav ul { list-style: none; margin: 0; padding: 0; }
        .mobile-drawer-nav li { border-bottom: 1px solid #222; }
        .mobile-drawer-nav li a { display: block; padding: 14px 0; color: #fff; font-weight: 600; font-size: 14px; text-transform: uppercase; font-family: 'Open Sans', sans-serif; }
        .mobile-drawer-nav li.active a { color: #fd961a; }
        .mobile-drawer-actions { margin: 25px 0; }
        .mobile-drawer-actions .btn { margin-bottom: 12px; }
        .mobile-drawer-actions .mobile-drawer-ghost { background: transparent; border: 1px solid #fd961a; color: #fd961a; }
        .mobile-drawer-desc { color: #999; font-size: 13px; line-height: 22px; margin-bottom: 15px; }
        .mobile-drawer-email { color: #fd961a; font-size: 13px; margin-bottom: 20px; display: block; }
        .mobile-drawer-social { display: flex; gap: 12px; }
        .mobile-drawer-social a { display: flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 50%; background: #1a1a1a; color: #fff; }
        @media (min-width: 768px) {
            .mobile-drawer, .mobile-drawer-overlay { display: none !important; }
        }

        .btn-ghost-orange {
            background: transparent;
            border: 1px solid #fd961a;
            color: #fd961a;
        }
        .btn-ghost-orange:hover, .btn-ghost-orange:focus, .btn-ghost-orange:active {
            background: #fd961a;
            color: #fff;
        }
        .hero-cta { margin-top: 8px; }
        .hero-cta-row { display: flex; gap: 10px; }
        .hero-cta-row .btn { flex: 1; text-align: center; }
        .hero-cta-row .btn-ghost-orange .fa {
            margin-left: 4px;
        }

        /* Hero content: left-aligned badge + headline + lead copy + stat row +
           CTA buttons + trust line, replacing the old centered headline-only hero. */
        #main-slide .slider-content {
            position: absolute;
            top: 0;
            left: 0;
            margin-top: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
        }
        @media (min-width: 768px) {
            /* The richer hero content (badge/lead/stats/trust line) is taller
               than style.css's original 570px assumption. Centering it
               vertically let the top (the badge) drift up underneath the
               transparent fixed header, so anchor it from the top with a
               fixed offset instead — reliable regardless of content height. */
            #main-slide .item {
                min-height: 760px;
            }
            #main-slide .slider-content {
                align-items: flex-start;
                padding-top: 140px;
            }
            /* Also trim the headline so it wraps to 2 lines, not 3, inside
               the narrower 640px content column (it used to span the full,
               centered container width). */
            #main-slide .slider-content h3.slide-title {
                font-size: 50px;
                line-height: 58px;
            }
        }
        .hero-text-block {
            max-width: 640px;
            text-align: left;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #fd961a;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 18px;
        }
        .hero-badge .fa-circle {
            font-size: 8px;
        }
        .hero-lead {
            color: rgba(255, 255, 255, .75);
            font-size: 16px;
            line-height: 26px;
            max-width: 520px;
            margin: 18px 0 0;
        }
        .hero-stats {
            display: flex;
            gap: 40px;
            margin: 26px 0;
        }
        .hero-stat strong {
            display: block;
            color: #fff;
            font-size: 26px;
            font-weight: 800;
        }
        .hero-stat span {
            display: block;
            color: rgba(255, 255, 255, .6);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-top: 4px;
        }
        .hero-trust {
            color: rgba(255, 255, 255, .55);
            font-size: 13px;
            line-height: 20px;
            max-width: 480px;
            margin: 20px 0 0;
        }

        @media (max-width: 767px) {
            /* Full-height mobile hero so the richer content below has room. */
            #main-slide .item {
                min-height: 100vh;
            }
            #main-slide .slider-content h3.slide-title {
                font-size: 32px;
                line-height: 40px;
            }
            .hero-lead {
                font-size: 14px;
                line-height: 22px;
            }
            .hero-stats {
                gap: 28px;
                margin: 20px 0;
            }
            .hero-stat strong {
                font-size: 22px;
            }
            /* Stack the CTA buttons full-width instead of side by side, and
               drop the trailing chevron — it skewed the "Sign In" text off
               visual-center even though it was text-align:center. */
            .hero-cta-row {
                flex-direction: column;
            }
            .hero-cta-row .btn-ghost-orange .fa {
                display: none;
            }
        }
    </style>

</head>

<body class="hero-header">
    <!-- SVG Preloader Starts -->
    <div id="preloader">
        <div id="preloader-content">
            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="150px" height="150px" viewBox="100 100 400 400" xml:space="preserve">
                <filter id="dropshadow" height="130%">
                <feGaussianBlur in="SourceAlpha" stdDeviation="5"/>
                <feOffset dx="0" dy="0" result="offsetblur"/>
                <feFlood flood-color="red"/>
                <feComposite in2="offsetblur" operator="in"/>
                <feMerge>
                <feMergeNode/>
                <feMergeNode in="SourceGraphic"/>
                </feMerge>
                </filter>
                <path class="path" fill="#000000" d="M446.089,261.45c6.135-41.001-25.084-63.033-67.769-77.735l13.844-55.532l-33.801-8.424l-13.48,54.068
                    c-8.896-2.217-18.015-4.304-27.091-6.371l13.568-54.429l-33.776-8.424l-13.861,55.521c-7.354-1.676-14.575-3.328-21.587-5.073
                    l0.034-0.171l-46.617-11.64l-8.993,36.102c0,0,25.08,5.746,24.549,6.105c13.689,3.42,16.159,12.478,15.75,19.658L208.93,357.23
                    c-1.675,4.158-5.925,10.401-15.494,8.031c0.338,0.485-24.579-6.134-24.579-6.134l-9.631,40.468l36.843,9.188
                    c8.178,2.051,16.209,4.19,24.098,6.217l-13.978,56.17l33.764,8.424l13.852-55.571c9.235,2.499,18.186,4.813,26.948,6.995
                    l-13.802,55.309l33.801,8.424l13.994-56.061c57.648,10.902,100.998,6.502,119.237-45.627c14.705-41.979-0.731-66.193-31.06-81.984
                    C425.008,305.984,441.655,291.455,446.089,261.45z M368.859,369.754c-10.455,41.983-81.128,19.285-104.052,13.589l18.562-74.404
                    C306.28,314.65,379.774,325.975,368.859,369.754z M379.302,260.846c-9.527,38.187-68.358,18.781-87.442,14.023l16.828-67.489
                    C327.767,212.14,389.234,221.02,379.302,260.846z"/>
            </svg>
        </div>
    </div>
    <!-- SVG Preloader Ends -->
    <!-- Wrapper Starts -->
    <div class="wrapper">
        <!-- Header Starts -->
        <header class="header">
            <div class="container">
                <div class="row header-row-inline">
                    <!-- Logo Starts -->
                    <div class="main-logo col-xs-6 col-md-2">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('images/logo.svg') }}" alt="VelocityMarkets" class="brand-logo">
                        </a>
                    </div>
                    <!-- Logo Ends -->
                    <!-- Nav Links Starts -->
                    <div class="col-xs-12 col-md-7">
                        <button type="button" class="navbar-toggle" id="mobile-drawer-toggle">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <div class="collapse navbar-collapse navbar-responsive-collapse">
                            <ul class="nav navbar-nav header-nav-inline">
                                <li @class(['active' => request()->routeIs('home')])><a href="{{ route('home') }}">Home</a></li>
                                <li @class(['active' => request()->routeIs('about')])><a href="{{ route('about') }}">About Us</a></li>
                                <li @class(['active' => request()->routeIs('services')])><a href="{{ route('services') }}">Services</a></li>
                                <li @class(['active' => request()->routeIs('pricing')])><a href="{{ route('pricing') }}">Pricing</a></li>
                                <li @class(['active' => request()->routeIs('contact')])><a href="{{ route('contact') }}">Contact</a></li>
                            </ul>
                        </div>
                    </div>
                    <!-- Nav Links Ends -->
                    <!-- User Sign In/Sign Up Starts -->
                    <div class="col-xs-6 col-md-3 text-right">
                        <ul class="unstyled user">
                            @auth
                                <li class="sign-up"><a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="fa fa-tachometer"></i> dashboard</a></li>
                            @else
                                <li class="sign-in"><a href="{{ route('login') }}" class="btn btn-primary"><i class="fa fa-user"></i> sign in</a></li>
                                <li class="sign-up"><a href="{{ route('register') }}" class="btn btn-primary"><i class="fa fa-user-plus"></i> register</a></li>
                            @endauth
                        </ul>
                    </div>
                    <!-- User Sign In/Sign Up Ends -->
                </div>
            </div>
        </header>
        <!-- Header Ends -->

        <!-- Mobile Drawer Starts -->
        <div class="mobile-drawer-overlay" id="mobile-drawer-overlay"></div>
        <aside class="mobile-drawer" id="mobile-drawer">
            <button type="button" class="mobile-drawer-close" id="mobile-drawer-close" aria-label="Close menu">&times;</button>
            <div class="mobile-drawer-logo">
                <img src="{{ asset('images/logo.svg') }}" alt="VelocityMarkets" class="brand-logo">
            </div>
            <nav class="mobile-drawer-nav">
                <ul>
                    <li @class(['active' => request()->routeIs('home')])><a href="{{ route('home') }}">Home</a></li>
                    <li @class(['active' => request()->routeIs('about')])><a href="{{ route('about') }}">About Us</a></li>
                    <li @class(['active' => request()->routeIs('services')])><a href="{{ route('services') }}">Services</a></li>
                    <li @class(['active' => request()->routeIs('pricing')])><a href="{{ route('pricing') }}">Pricing</a></li>
                    <li @class(['active' => request()->routeIs('contact')])><a href="{{ route('contact') }}">Contact</a></li>
                </ul>
            </nav>
            <div class="mobile-drawer-actions">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-block"><i class="fa fa-tachometer"></i> Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-block mobile-drawer-ghost">Sign In</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-block">Get Started</a>
                @endauth
            </div>
            <p class="mobile-drawer-desc">VelocityMarkets is a modern crypto trading platform for trading, investing and growing your crypto portfolio.</p>
            <p class="mobile-drawer-email">support@velocitymarkets.com</p>
            <div class="mobile-drawer-social">
                <a href="#" target="_blank"><i class="fa fa-facebook"></i></a>
                <a href="#" target="_blank"><i class="fa fa-twitter"></i></a>
                <a href="#" target="_blank"><i class="fa fa-google-plus"></i></a>
                <a href="#" target="_blank"><i class="fa fa-linkedin"></i></a>
            </div>
        </aside>
        <!-- Mobile Drawer Ends -->

        {{ $slot }}

        <!-- Footer Starts -->
        <footer class="footer">
            <!-- Footer Top Area Starts -->
            <div class="top-footer">
                <div class="container">
                    <div class="row">
                        <!-- Footer Widget Starts -->
                        <div class="col-sm-4 col-md-2">
                            <h4>Our Company</h4>
                            <div class="menu">
                                <ul>
                                    <li><a href="{{ route('home') }}">Home</a></li>
                                    <li><a href="{{ route('about') }}">About</a></li>
                                    <li><a href="{{ route('services') }}">Services</a></li>
                                    <li><a href="{{ route('pricing') }}">Pricing</a></li>
                                    <li><a href="#">Blog</a></li>
                                    <li><a href="{{ route('contact') }}">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Footer Widget Ends -->
                        <!-- Footer Widget Starts -->
                        <div class="col-sm-4 col-md-2">
                            <h4>Help & Support</h4>
                            <div class="menu">
                                <ul>
                                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                                    <li><a href="{{ route('terms') }}">Terms of Services</a></li>
                                    <li><a href="#">404</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="#">Coming Soon</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Footer Widget Ends -->
                        <!-- Footer Widget Starts -->
                        <div class="col-sm-4 col-md-3">
                            <h4>Contact Us </h4>
                            <div class="contacts">
                                <div>
                                    <span>support@velocitymarkets.com</span>
                                </div>
                                <div>
                                    <span>+1 (302) 555-0147</span>
                                </div>
                                <div>
                                    <span>New York, USA</span>
                                </div>
                                <div>
                                    <span>mon-fri 08am &#x21FE; 08pm (UTC)</span>
                                </div>
                            </div>
                            <!-- Social Media Profiles Starts -->
                            <div class="social-footer">
                                <ul>
                                    <li><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="#" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                            <!-- Social Media Profiles Ends -->
                        </div>
                        <!-- Footer Widget Ends -->
                        <!-- Footer Widget Starts -->
                        <div class="col-sm-12 col-md-5">
                            <!-- Facts Starts -->
                            <div class="facts-footer">
                                <div>
                                    <h5>$198.76B</h5>
                                    <span>Market cap</span>
                                </div>
                                <div>
                                    <h5>243K</h5>
                                    <span>daily transactions</span>
                                </div>
                                <div>
                                    <h5>369K</h5>
                                    <span>active accounts</span>
                                </div>
                                <div>
                                    <h5>127</h5>
                                    <span>supported countries</span>
                                </div>
                            </div>
                            <!-- Facts Ends -->
                            <hr>
                            <!-- Supported Payment Cards Logo Starts -->
                            <div class="payment-logos">
                                <h4 class="payment-title">supported payment methods</h4>
                                <img src="{{ asset('assets/bayya/images/icons/payment/american-express.png') }}" alt="american-express">
                                <img src="{{ asset('assets/bayya/images/icons/payment/mastercard.png') }}" alt="mastercard">
                                <img src="{{ asset('assets/bayya/images/icons/payment/visa.png') }}" alt="visa">
                                <img src="{{ asset('assets/bayya/images/icons/payment/paypal.png') }}" alt="paypal">
                                <img class="last" src="{{ asset('assets/bayya/images/icons/payment/maestro.png') }}" alt="maestro">
                            </div>
                            <!-- Supported Payment Cards Logo Ends -->
                        </div>
                        <!-- Footer Widget Ends -->
                    </div>
                </div>
            </div>
            <!-- Footer Top Area Ends -->
            <!-- Footer Bottom Area Starts -->
            <div class="bottom-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12">
                            <!-- Copyright Text Starts -->
                            <p class="text-center">Copyright &copy; {{ date('Y') }} VelocityMarkets All Rights Reserved | Design adapted from a template by <a href="https://themeforest.net/user/celtano" target="_blank">celtano</a></p>
                            <!-- Copyright Text Ends -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Bottom Area Ends -->
        </footer>
        <!-- Footer Ends -->
        <!-- Back To Top Starts  -->
        <a href="#" id="back-to-top" class="back-to-top fa fa-arrow-up"></a>
        <!-- Back To Top Ends  -->

        <!-- Template JS Files -->
        <script src="{{ asset('assets/bayya/js/jquery-2.2.4.min.js') }}"></script>
        <script src="{{ asset('assets/bayya/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/bayya/js/select2.min.js') }}"></script>
        <script src="{{ asset('assets/bayya/js/jquery.magnific-popup.min.js') }}"></script>
        <script src="{{ asset('assets/bayya/js/custom.js') }}"></script>

        <script>
        (function () {
            var toggle = document.getElementById('mobile-drawer-toggle');
            var drawer = document.getElementById('mobile-drawer');
            var overlay = document.getElementById('mobile-drawer-overlay');
            var closeBtn = document.getElementById('mobile-drawer-close');
            if (!toggle || !drawer || !overlay || !closeBtn) { return; }
            // custom.js also binds a click handler to ".navbar-toggle" that toggles the
            // "overflow-hidden" class on <html> — this button keeps that class for its
            // hamburger-icon styling, so that old handler still fires alongside these and
            // can flip "overflow-hidden" back off right after this code sets it. Rather
            // than fight over exactly when/whether that handler is attached, force the
            // correct final state on a fresh tick, after every click handler for this
            // event (old and new) has finished running.
            function openDrawer() {
                drawer.classList.add('open');
                overlay.classList.add('open');
                document.documentElement.classList.add('overflow-hidden');
                setTimeout(function () {
                    document.documentElement.classList.add('overflow-hidden');
                }, 0);
            }
            function closeDrawer() {
                drawer.classList.remove('open');
                overlay.classList.remove('open');
                setTimeout(function () {
                    document.documentElement.classList.remove('overflow-hidden');
                }, 0);
            }
            toggle.addEventListener('click', openDrawer);
            closeBtn.addEventListener('click', closeDrawer);
            overlay.addEventListener('click', closeDrawer);
        })();

        (function () {
            // Marketing header: transparent over the hero/banner, solid + fixed once scrolled.
            var header = document.querySelector('.header');
            if (!header || !document.body.classList.contains('hero-header')) { return; }
            var SCROLL_THRESHOLD = 80;
            function onScroll() {
                if (window.scrollY > SCROLL_THRESHOLD) {
                    header.classList.add('header-scrolled');
                } else {
                    header.classList.remove('header-scrolled');
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        })();
        </script>

    </div>
    <!-- Wrapper Ends -->
</body>

</html>
