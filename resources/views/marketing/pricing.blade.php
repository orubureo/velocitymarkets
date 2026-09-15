<x-layouts.marketing
    title="Pricing — VelocityMarkets"
    description="Compare VelocityMarkets investment plans — minimum and maximum stake, total return and duration — and choose the plan that fits your goals."
>

    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="banner-overlay">
            <div class="banner-text text-center">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h2 class="title-head">our <span>plans</span></h2>
                            <hr>
                            <ul class="breadcrumb">
                                <li><a href="{{ route('home') }}">home</a></li>
                                <li>pricing</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area Ends -->

    <!-- Pricing Starts -->
    <section class="pricing">
        <div class="container">

            <h3 class="text-center">Investment Plans</h3>
            <p class="text-center">Pick a plan that matches your goals, fund it in crypto, and let VelocityMarkets put it to work for you.</p>

            @if ($plans->isEmpty())
                <div class="row">
                    <div class="col-xs-12 text-center" style="padding: 60px 0;">
                        <p>No investment plans are available right now. Check back soon.</p>
                    </div>
                </div>
            @else
                <div class="row pricing-tables-content pricing-page">
                    <div class="pricing-container">
                        <!-- Pricing Tables Starts -->
                        <ul class="pricing-list bounce-invert">
                            @foreach ($plans as $plan)
                                @php
                                    $roi = rtrim(rtrim(number_format($plan->roi_percent, 2), '0'), '.');
                                @endphp
                                <li class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                                    <ul class="pricing-wrapper">
                                        <!-- Pricing Table Starts -->
                                        <li>
                                            <header class="pricing-header">
                                                <h2>{{ $plan->name }} <span>total return</span></h2>
                                                <div class="price">
                                                    <span class="value">{{ $roi }}</span><span class="currency">%</span>
                                                </div>
                                            </header>
                                            <div class="pricing-body">
                                                <ul class="pricing-features unstyled">
                                                    <li><em>Minimum</em> ${{ number_format($plan->min_amount) }}</li>
                                                    <li><em>Maximum</em> {{ $plan->max_amount ? '$' . number_format($plan->max_amount) : 'No limit' }}</li>
                                                    <li><em>Duration</em> {{ $plan->duration_days }} {{ Str::plural('day', $plan->duration_days) }}</li>
                                                </ul>
                                                @if ($plan->description)
                                                    <p>{{ $plan->description }}</p>
                                                @endif
                                            </div>
                                            <footer class="pricing-footer">
                                                @guest
                                                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                                                @else
                                                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Go To Dashboard</a>
                                                @endguest
                                            </footer>
                                        </li>
                                        <!-- Pricing Table Ends -->
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

        </div>
    </section>
    <!-- Pricing Ends -->

    <!-- Call To Action Section Starts -->
    <section class="call-action-all">
        <div class="call-action-all-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="action-text">
                            <h2>Get Started Today With VelocityMarkets</h2>
                            <p class="lead">Open a free account and start trading, copy trading, or investing today!</p>
                        </div>
                        <p class="action-btn"><a class="btn btn-primary" href="{{ route('register') }}">Register Now</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Call To Action Section Ends -->

</x-layouts.marketing>
