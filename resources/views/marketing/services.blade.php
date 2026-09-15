<x-layouts.marketing
    title="Platform — VelocityMarkets"
    description="Explore the VelocityMarkets platform: spot trading, copy trading, investment plans, and crypto deposits and withdrawals across BTC, ETH and USDT."
>

    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="banner-overlay">
            <div class="banner-text text-center">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h2 class="title-head">our <span>platform</span></h2>
                            <hr>
                            <ul class="breadcrumb">
                                <li><a href="{{ route('home') }}">home</a></li>
                                <li>platform</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area Ends -->

    <!-- Section Services Starts -->
    <section class="services">
        <div class="container">
            <div class="row">
                <!-- Service Box Starts -->
                <div class="col-md-6 service-box">
                    <div>
                        <img id="buy-sell-bitcoins" src="{{ asset('assets/bayya/images/icons/orange/buy-sell-bitcoins.png') }}" alt="spot trading">
                        <div class="service-box-content">
                            <h3>Spot Trading</h3>
                            <p>Trade Bitcoin, Ethereum and other major cryptocurrencies against USDT with live order books, real-time charts and tight spreads. Place market and limit orders instantly and manage every position from a single dashboard.</p>
                        </div>
                    </div>
                </div>
                <!-- Service Box Ends -->
                <!-- Service Box Starts -->
                <div class="col-md-6 service-box">
                    <div>
                        <img id="world-coverage" src="{{ asset('assets/bayya/images/icons/orange/world-coverage.png') }}" alt="copy trading">
                        <div class="service-box-content">
                            <h3>Copy Trading</h3>
                            <p>Follow and automatically mirror the strategies of verified, top-performing traders from around the world. Allocate any amount, set your own risk limits, and let proven strategies trade for you around the clock.</p>
                        </div>
                    </div>
                </div>
                <!-- Service Box Ends -->
                <!-- Service Box Starts -->
                <div class="col-md-6 service-box">
                    <div>
                        <img id="add-bitcoins" src="{{ asset('assets/bayya/images/icons/orange/add-bitcoins.png') }}" alt="investment plans">
                        <div class="service-box-content">
                            <h3>Investment Plans</h3>
                            <p>Choose from a range of managed investment plans built for different risk appetites and time horizons. Track projected returns, reinvest profits automatically, and grow your portfolio without watching the market every minute.</p>
                        </div>
                    </div>
                </div>
                <!-- Service Box Ends -->
                <!-- Service Box Starts -->
                <div class="col-md-6 service-box">
                    <div>
                        <img id="payment-options" src="{{ asset('assets/bayya/images/icons/orange/payment-options.png') }}" alt="crypto deposits and withdrawals">
                        <div class="service-box-content">
                            <h3>Crypto Deposits &amp; Withdrawals</h3>
                            <p>Fund your account or cash out in Bitcoin, Ethereum and USDT, with USDT supported over TRC20, ERC20 and BEP20 networks. Fast confirmations and transparent network fees keep your funds moving exactly when you need them.</p>
                        </div>
                    </div>
                </div>
                <!-- Service Box Ends -->
            </div>
        </div>
    </section>
    <!-- Section Services Ends -->

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
