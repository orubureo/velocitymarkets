<x-layouts.marketing
    title="About — VelocityMarkets"
    description="Learn about VelocityMarkets, the modern platform for crypto trading, copy trading and investment plans."
>

    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="banner-overlay">
            <div class="banner-text text-center">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h2 class="title-head">About <span>Us</span></h2>
                            <hr>
                            <ul class="breadcrumb">
                                <li><a href="{{ route('home') }}">home</a></li>
                                <li>About</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area Ends -->

    <!-- About Section Starts -->
    <section class="about-page">
        <div class="container">
            <div class="row about-content">
                <div class="col-sm-12 col-md-5 col-lg-6 text-center">
                    <img id="about-us" class="img-responsive img-about-us" src="{{ asset('assets/bayya/images/about-us.png') }}" alt="about VelocityMarkets">
                </div>
                <div class="col-sm-12 col-md-7 col-lg-6">
                    <div class="feature-about">
                        <h3 class="title-about">We are VelocityMarkets</h3>
                        <p>VelocityMarkets is a modern trading platform built for people who want more than a single-purpose exchange. Deposit crypto, place spot trades, follow top-performing traders with copy trading, or put your funds to work in a curated investment plan — all from one dashboard. Join a growing global community of traders who trust us with their strategy.</p>
                    </div>
                    <div class="feature-about">
                        <h3 class="title-about risk-title"><i class="fa fa-warning"></i> risk warning</h3>
                        <p>Cryptocurrencies and other digital assets are volatile and are not legal tender in most jurisdictions. Trading, copy trading and investment plans carry risk, and account balances are not covered by any government-backed deposit insurance or protection scheme.</p>
                    </div>
                    <a class="btn btn-primary btn-services" href="{{ route('services') }}">Our platform</a>
                </div>
            </div>
        </div>
    </section>
    <!-- About Section Ends -->

    <!-- Facts Section Starts -->
    <section class="facts">
        <div class="container">
            <div class="row text-center facts-content">
                <div class="text-center heading-facts">
                    <h2>VelocityMarkets<span> numbers</span></h2>
                    <p>a trading platform built for traders, copy traders and investors alike</p>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-6 fact">
                    <h3>$77.45B</h3>
                    <h4>volume processed</h4>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-6 fact fact-clear">
                    <h3>165K</h3>
                    <h4>daily transactions</h4>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-6 fact">
                    <h3>12K+</h3>
                    <h4>active traders</h4>
                </div>
                <div class="col-xs-12 col-md-3 col-sm-6">
                    <h3>140+</h3>
                    <h4>countries supported</h4>
                </div>
                <div class="col-xs-12 buttons">
                    <a class="btn btn-primary btn-pricing" href="{{ route('pricing') }}">See pricing</a>
                    <span class="or"> or </span>
                    <a class="btn btn-primary btn-register" href="{{ route('register') }}">Register Now</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Facts Section Ends -->

    <!-- Team Section Starts -->
    <section class="team">
        <div class="container">
            <div class="row text-center">
                <h2 class="title-head">our <span>experts</span></h2>
                <div class="title-head-subtitle">
                    <p>A talented team of trading and market experts building VelocityMarkets</p>
                </div>
            </div>
            <div class="row team-content team-members">
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="team-member">
                        <img src="{{ asset('assets/bayya/images/team/member1.jpg') }}" class="img-responsive" alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Maryana Mori</h4>
                            <p>Ceo &amp; Founder</p>
                            <ul class="list list-inline social">
                                <li><a href="#" class="fa fa-facebook"></a></li>
                                <li><a href="#" class="fa fa-twitter"></a></li>
                                <li><a href="#" class="fa fa-google-plus"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="team-member">
                        <img src="{{ asset('assets/bayya/images/team/member2.jpg') }}" class="img-responsive" alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Marco Verratti</h4>
                            <p>Director of Trading</p>
                            <ul class="list list-inline social">
                                <li><a href="#" class="fa fa-facebook"></a></li>
                                <li><a href="#" class="fa fa-twitter"></a></li>
                                <li><a href="#" class="fa fa-google-plus"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="team-member">
                        <img src="{{ asset('assets/bayya/images/team/member3.jpg') }}" class="img-responsive" alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Emilia Bella</h4>
                            <p>Head of Investments</p>
                            <ul class="list list-inline social">
                                <li><a href="#" class="fa fa-facebook"></a></li>
                                <li><a href="#" class="fa fa-twitter"></a></li>
                                <li><a href="#" class="fa fa-google-plus"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="team-member">
                        <img src="{{ asset('assets/bayya/images/team/member4.jpg') }}" class="img-responsive" alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Antonio Conte</h4>
                            <p>Lead Platform Engineer</p>
                            <ul class="list list-inline social">
                                <li><a href="#" class="fa fa-facebook"></a></li>
                                <li><a href="#" class="fa fa-twitter"></a></li>
                                <li><a href="#" class="fa fa-google-plus"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Team Section Ends -->

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
