<x-layouts.marketing title="VelocityMarkets - Modern Trading Platform"
    description="VelocityMarkets is a commercial website for trading, investing and growing your crypto portfolio.">

    <!-- Slider Starts -->
    <div id="main-slide" class="carousel slide carousel-fade" data-ride="carousel">
        <!-- Indicators Starts -->
        <ol class="carousel-indicators visible-lg visible-md">
            <li data-target="#main-slide" data-slide-to="0" class="active"></li>
            <li data-target="#main-slide" data-slide-to="1"></li>
        </ol>
        <!-- Indicators Ends -->
        <!-- Carousel Inner Starts -->
        <div class="carousel-inner">
            <!-- Carousel Item Starts -->
            <div class="item active bg-parallax item-2">
                <div class="slider-content">
                    <div class="container">
                        <div class="slider-text hero-text-block">
                            <h3 class="slide-title"><span>Trading Platform</span> <br />You can <span>Trust</span> </h3>
                            <p class="hero-lead">Instant trade execution at a fair price, transparent payouts, and an
                                admin-reviewed deposit &amp; withdrawal flow &mdash; built for traders who value
                                reliability.</p>
                            <div class="hero-stats">
                                <div class="hero-stat">
                                    <strong>700K+</strong>
                                    <span>Traders Worldwide</span>
                                </div>
                                <div class="hero-stat">
                                    <strong>24/7</strong>
                                    <span>Market Access</span>
                                </div>
                            </div>
                            <div class="hero-cta">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i
                                            class="fa fa-tachometer"></i> Go To Dashboard</a>
                                @else
                                    <div class="hero-cta-row">
                                        <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                                        <a href="{{ route('login') }}" class="btn btn-ghost-orange">Sign In <i
                                                class="fa fa-angle-right"></i></a>
                                    </div>
                                @endauth
                            </div>
                            <p class="hero-trust">Trade on Bitcoin, Gold, Oil, Apple, Tesla, crude oil and 6,400+ other
                                world-renowned markets.
                        </div>
                    </div>
                </div>
            </div>
            <!-- Carousel Item Ends -->
            <!-- Carousel Item Starts -->
            <div class="item bg-parallax item-1">
                <div class="slider-content">
                    <div class="container">
                        <div class="slider-text hero-text-block">
                            <h3 class="slide-title"><span>Secure</span> and <span>Easy Way</span><br /> To Trade</h3>
                            <p class="hero-lead">Trade spot markets yourself, copy a top-performing trader
                                automatically, or put your funds into a fixed-term investment plan &mdash; all from one
                                dashboard.</p>
                            <div class="hero-stats">
                                <div class="hero-stat">
                                    <strong>700K+</strong>
                                    <span>Traders Worldwide</span>
                                </div>
                                <div class="hero-stat">
                                    <strong>24/7</strong>
                                    <span>Market Access</span>
                                </div>
                            </div>
                            <div class="hero-cta">
                                @auth
                                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i
                                            class="fa fa-tachometer"></i> Go To Dashboard</a>
                                @else
                                    <div class="hero-cta-row">
                                        <a href="{{ route('register') }}" class="btn btn-primary">Create Account</a>
                                        <a href="{{ route('login') }}" class="btn btn-ghost-orange">Sign In <i
                                                class="fa fa-angle-right"></i></a>
                                    </div>
                                @endauth
                            </div>
                            <p class="hero-trust">Trade on Bitcoin, Gold, Oil, Apple, Tesla, crude oil and 6,400+ other
                                world-renowned markets.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Carousel Item Ends -->
        </div>
        <!-- Carousel Inner Ends -->
        <!-- Carousel Controlers Starts -->
        <a class="left carousel-control" href="#main-slide" data-slide="prev">
            <span><i class="fa fa-angle-left"></i></span>
        </a>
        <a class="right carousel-control" href="#main-slide" data-slide="next">
            <span><i class="fa fa-angle-right"></i></span>
        </a>
        <!-- Carousel Controlers Ends -->
    </div>
    <!-- Slider Ends -->
    <!-- Features Section Starts -->
    <section class="features">
        <div class="container">
            <div class="row features-row">
                <!-- Feature Box Starts -->
                <div class="feature-box col-md-4 col-sm-12">
                    <span class="feature-icon">
                        <img id="download-bitcoin"
                            src="{{ asset('assets/bayya/images/icons/orange/download-bitcoin.png') }}"
                            alt="create account">
                    </span>
                    <div class="feature-box-content">
                        <h3>Create Account</h3>
                        <p>Sign up in minutes on PC or Mobile to start trading crypto.</p>
                    </div>
                </div>
                <!-- Feature Box Ends -->
                <!-- Feature Box Starts -->
                <div class="feature-box two col-md-4 col-sm-12">
                    <span class="feature-icon">
                        <img id="add-bitcoins" src="{{ asset('assets/bayya/images/icons/orange/add-bitcoins.png') }}"
                            alt="fund your account">
                    </span>
                    <div class="feature-box-content">
                        <h3>Fund Your Account</h3>
                        <p>Deposit crypto you&rsquo;ve acquired or fund your account via credit card.</p>
                    </div>
                </div>
                <!-- Feature Box Ends -->
                <!-- Feature Box Starts -->
                <div class="feature-box three col-md-4 col-sm-12">
                    <span class="feature-icon">
                        <img id="buy-sell-bitcoins"
                            src="{{ asset('assets/bayya/images/icons/orange/buy-sell-bitcoins.png') }}"
                            alt="trade, copy trade and invest">
                    </span>
                    <div class="feature-box-content">
                        <h3>Trade, Copy & Invest</h3>
                        <p>Place a trade, follow a top trader, or drop into an investment plan.</p>
                    </div>
                </div>
                <!-- Feature Box Ends -->
            </div>
        </div>
    </section>
    <!-- Features Section Ends -->
    <!-- About Section Starts -->
    <section class="about-us">
        <div class="container">
            <!-- Section Title Starts -->
            <div class="row text-center">
                <h2 class="title-head">About <span>Us</span></h2>
                <div class="title-head-subtitle">
                    <p>a commercial website for trading, investing and growing your crypto portfolio</p>
                </div>
            </div>
            <!-- Section Title Ends -->
            <!-- Section Content Starts -->
            <div class="row about-content">
                <!-- Image Starts -->
                <div class="col-sm-12 col-md-5 col-lg-6 text-center">
                    <img id="about-us" class="img-responsive img-about-us"
                        src="{{ asset('assets/bayya/images/about-us.png') }}" alt="about us">
                </div>
                <!-- Image Ends -->
                <!-- Content Starts -->
                <div class="col-sm-12 col-md-7 col-lg-6">
                    <h3 class="title-about">WE ARE VELOCITYMARKETS</h3>
                    <p class="about-text">A place for everyone who wants to trade, invest and grow their crypto. Deposit
                        funds using your Visa/MasterCard, bank transfer or crypto wallet. Instant trade execution at a
                        fair price is guaranteed, with copy trading and investment plans built in. Join over 700,000
                        users from all over the world satisfied with our services.</p>
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#menu1">Our Mission</a></li>
                        <li><a data-toggle="tab" href="#menu2">Our advantages</a></li>
                        <li><a data-toggle="tab" href="#menu3">Our guarantees</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="menu1" class="tab-pane fade in active">
                            <p>Crypto markets run on the blockchain, a protocol that allows anyone to create, transfer
                                and verify ultra-secure financial data without a middleman. Our mission is to make
                                trading that world simple.</p>
                        </div>
                        <div id="menu2" class="tab-pane fade">
                            <p>Our mission is to help you enter and better understand the world of crypto trading, copy
                                trading and investing, and avoid any issues you may encounter along the way.</p>
                        </div>
                        <div id="menu3" class="tab-pane fade">
                            <p>We are here because we are passionate about open, transparent markets and aim to be a
                                major driving force in widespread adoption, we are the first and the best in crypto
                                trading. </p>
                        </div>
                    </div>
                    <a class="btn btn-primary" href="{{ route('about') }}">Read More</a>
                </div>
                <!-- Content Ends -->
            </div>
            <!-- Section Content Ends -->
        </div>
    </section>
    <!-- About Section Ends -->
    <!-- Features and Video Section Starts -->
    <section class="image-block">
        <div class="container-fluid">
            <div class="row">
                <!-- Features Starts -->
                <div class="col-md-8 ts-padding img-block-left">
                    <div class="gap-20"></div>
                    <div class="row">
                        <!-- Feature Starts -->
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="feature text-center">
                                <span class="feature-icon">
                                    <img id="strong-security"
                                        src="{{ asset('assets/bayya/images/icons/orange/strong-security.png') }}"
                                        alt="strong security" />
                                </span>
                                <h3 class="feature-title">Strong Security</h3>
                                <p>Protection against DDoS attacks, <br>full data encryption</p>
                            </div>
                        </div>
                        <!-- Feature Ends -->
                        <div class="gap-20-mobile"></div>
                        <!-- Feature Starts -->
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="feature text-center">
                                <span class="feature-icon">
                                    <img id="world-coverage"
                                        src="{{ asset('assets/bayya/images/icons/orange/world-coverage.png') }}"
                                        alt="world coverage" />
                                </span>
                                <h3 class="feature-title">World Coverage</h3>
                                <p>Providing services in 99% countries<br> around all the globe</p>
                            </div>
                        </div>
                        <!-- Feature Ends -->
                    </div>
                    <div class="gap-20"></div>
                    <div class="row">
                        <!-- Feature Starts -->
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="feature text-center">
                                <span class="feature-icon">
                                    <img id="payment-options"
                                        src="{{ asset('assets/bayya/images/icons/orange/payment-options.png') }}"
                                        alt="payment options" />
                                </span>
                                <h3 class="feature-title">Payment Options</h3>
                                <p>Popular methods: Visa, MasterCard, <br>bank transfer, cryptocurrency</p>
                            </div>
                        </div>
                        <!-- Feature Ends -->
                        <div class="gap-20-mobile"></div>
                        <!-- Feature Starts -->
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="feature text-center">
                                <span class="feature-icon">
                                    <img id="mobile-app"
                                        src="{{ asset('assets/bayya/images/icons/orange/mobile-app.png') }}"
                                        alt="trade on the go" />
                                </span>
                                <h3 class="feature-title">Trade On The Go</h3>
                                <p>Full trading, investing and copy-trading<br> access from any device</p>
                            </div>
                        </div>
                        <!-- Feature Ends -->
                    </div>
                    <div class="gap-20"></div>
                    <div class="row">
                        <!-- Feature Starts -->
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="feature text-center">
                                <span class="feature-icon">
                                    <img id="cost-efficiency"
                                        src="{{ asset('assets/bayya/images/icons/orange/cost-efficiency.png') }}"
                                        alt="cost efficiency" />
                                </span>
                                <h3 class="feature-title">Cost efficiency</h3>
                                <p>Reasonable trading fees for takers<br> and all market makers</p>
                            </div>
                        </div>
                        <!-- Feature Ends -->
                        <div class="gap-20-mobile"></div>
                        <!-- Feature Starts -->
                        <div class="col-sm-6 col-md-6 col-xs-12">
                            <div class="feature text-center">
                                <span class="feature-icon">
                                    <img id="high-liquidity"
                                        src="{{ asset('assets/bayya/images/icons/orange/high-liquidity.png') }}"
                                        alt="high liquidity" />
                                </span>
                                <h3 class="feature-title">High Liquidity</h3>
                                <p>Fast access to deep liquidity<br> across major trading pairs</p>
                            </div>
                        </div>
                        <!-- Feature Ends -->
                    </div>
                </div>
                <!-- Features Ends -->
                <!-- Video Starts -->
                <div class="col-md-4 ts-padding bg-image-1">
                    <div>
                        <div class="text-center">
                            <a class="button-video mfp-youtube" href="https://www.youtube.com/watch?v=jNQXAC9IVRw"></a>
                        </div>
                    </div>
                </div>
                <!-- Video Ends -->
            </div>
        </div>
    </section>
    <!-- Features and Video Section Ends -->
    <!-- Investment Plans Starts -->
    <section class="pricing">
        <div class="container">
            <!-- Section Title Starts -->
            <div class="row text-center">
                <h2 class="title-head">investment <span>plans</span></h2>
                <div class="title-head-subtitle">
                    <p>Choose a plan that matches your goals and start growing your portfolio</p>
                </div>
            </div>
            <!-- Section Title Ends -->
            <!-- Section Content Starts -->
            @php
                $investmentPlans = [
                    ['name' => 'Starter Plan', 'price' => '$500'],
                    ['name' => 'Basic Plan', 'price' => '$10,000'],
                    ['name' => 'Silver Plan', 'price' => '$100,000'],
                    ['name' => 'Gold Plan', 'price' => '$200,000'],
                    ['name' => 'Premium Plan', 'price' => '$500,000'],
                    ['name' => 'Diamond Plan', 'price' => '$700,000'],
                    ['name' => 'Ultimate Plan', 'price' => '$1,000,000'],
                ];
            @endphp
            <div class="investment-plans-row">
                @foreach ($investmentPlans as $plan)
                    <div class="investment-plan-card">
                        <span class="investment-plan-name">{{ $plan['name'] }}</span>
                        <div class="investment-plan-price">{{ $plan['price'] }}</div>
                        <a href="{{ route('register') }}" class="btn btn-primary">Invest Now</a>
                    </div>
                @endforeach
            </div>
            <!-- Section Content Ends -->
        </div>
    </section>
    <!-- Investment Plans Ends -->
    <!-- Crypto Calculator Section Starts -->
    <section class="bitcoin-calculator-section">
        <div class="container">
            <div class="row">
                <!-- Section Heading Starts -->
                <div class="col-md-12">
                    <h2 class="title-head text-center"><span>Crypto</span> Calculator</h2>
                    <p class="message text-center">Find out the current value of your crypto with our easy-to-use
                        converter</p>
                </div>
                <!-- Section Heading Ends -->
                {{--
                    The theme's original calculator relied on the select2 plugin (its bundled
                    select2.min.js throws "missing ./select2/core" — a broken build, unrelated
                    to anything in this app) plus a live client-side call to the defunct
                    blockchain.info/ticker API. Both failures meant the currency dropdown never
                    populated and no conversion ever ran. Rebuilt below as a small vanilla-JS
                    widget using real prices ($calculatorMarkets, from the same PriceService/
                    CoinGecko data the rest of the app uses) rendered server-side — no third-party
                    widget, no jQuery plugin dependency. The currency picker is a hand-built
                    dropdown (open/close, compact scrollable panel) styled to match the theme's
                    own orange-on-dark look, rather than a native <select> — with ~100 markets a
                    native select's browser-drawn list is huge and inconsistent across devices.
                --}}
                <!-- Calculator Form Starts -->
                <div class="col-md-12 text-center">
                    <form class="bitcoin-calculator" id="crypto-calculator" onsubmit="return false;">
                        <input class="form-input" type="text" inputmode="decimal" id="calc-amount" value="1" autocomplete="off">
                        <div class="form-info"><i class="fa fa-exchange"></i></div>
                        <div class="form-equal">=</div>
                        <input class="form-input form-input-result" type="text" inputmode="decimal" id="calc-result" autocomplete="off">
                        <div class="form-wrap calc-dropdown" data-calc-dropdown>
                            <button type="button" class="form-input select-currency select-primary calc-dropdown-trigger" id="calc-currency-trigger" aria-haspopup="listbox" aria-expanded="false">
                                <span class="calc-dropdown-label">{{ $calculatorMarkets[0]['display_name'] ?? '' }}</span>
                                <i class="fa fa-caret-down calc-dropdown-caret" aria-hidden="true"></i>
                            </button>
                            <ul class="calc-dropdown-panel" id="calc-currency-panel" role="listbox" hidden>
                                @foreach ($calculatorMarkets as $market)
                                    <li role="option" tabindex="0" class="calc-dropdown-option {{ $loop->first ? 'is-selected' : '' }}"
                                        data-symbol="{{ $market['symbol'] }}" data-price="{{ $market['price'] }}" data-label="{{ $market['display_name'] }}">
                                        {{ $market['display_name'] }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </form>
                    <p class="info"><i>* Live prices from our market data feed</i></p>
                </div>
                <!-- Calculator Form Ends -->
            </div>
        </div>
    </section>
    <!-- Crypto Calculator Section Ends -->

    <style>
        .calc-dropdown { position: relative; display: inline-block; }
        .calc-dropdown-trigger {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            background: #fd961a;
            border: 0;
            border-radius: 3px;
            padding: 0 10px;
        }
        .calc-dropdown-label {
            font-size: 13px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .calc-dropdown-caret {
            font-size: 12px;
            flex-shrink: 0;
            transition: transform .15s ease;
        }
        .calc-dropdown.is-open .calc-dropdown-caret { transform: rotate(180deg); }
        .calc-dropdown-panel {
            list-style: none;
            margin: 6px 0 0;
            padding: 4px 0;
            position: absolute;
            top: 100%;
            right: 0;
            z-index: 20;
            min-width: 100%;
            width: max-content;
            max-width: 220px;
            max-height: 230px;
            overflow-y: auto;
            background: #fd961a;
            border-radius: 4px;
            box-shadow: 0 12px 28px rgba(0,0,0,.4);
        }
        .calc-dropdown-panel[hidden] { display: none; }
        .calc-dropdown-option {
            padding: 9px 16px;
            font-size: 13px;
            font-weight: 700;
            color: #fff;
            white-space: nowrap;
            cursor: pointer;
        }
        .calc-dropdown-option:hover,
        .calc-dropdown-option.is-selected { background: rgba(0,0,0,.2); }

        @media (max-width: 767px) {
            .calc-dropdown-panel { max-width: 180px; }

            {{--
                The theme's own mobile rules pair [amount input + icon] and [result
                input + currency select] on two rows by relying on inline-block auto-
                wrap arithmetic (fixed icon/select widths + `calc(100% - 125px)` on
                the inputs) — tuned for its narrower select2-generated span. Our
                slightly wider custom dropdown trigger tips that arithmetic over by a
                few px and the second pair wraps onto its own row. Flexbox removes the
                guesswork: each pair is guaranteed to share a row regardless of the
                trigger's exact width, and `flex-basis: 100%` on the "=" forces it
                onto its own row in between.
            --}}
            #crypto-calculator {
                display: flex;
                flex-wrap: wrap;
                align-items: stretch;
                justify-content: center;
                gap: 8px;
            }
            #crypto-calculator > * { margin: 0 !important; }
            #crypto-calculator input.form-input {
                flex: 1 1 auto;
                width: auto;
                min-width: 0;
            }
            #crypto-calculator .form-info {
                flex: 0 0 auto;
            }
            #crypto-calculator .form-equal {
                flex: 1 1 100%;
                display: block;
                text-align: center;
                padding: 8px 0;
            }
            #crypto-calculator .calc-dropdown {
                flex: 0 0 auto;
            }
        }
    </style>

    <script>
        (function () {
            var amountInput = document.getElementById('calc-amount');
            var resultInput = document.getElementById('calc-result');
            var wrapper = document.querySelector('[data-calc-dropdown]');
            var trigger = document.getElementById('calc-currency-trigger');
            var label = trigger ? trigger.querySelector('.calc-dropdown-label') : null;
            var panel = document.getElementById('calc-currency-panel');

            if (!amountInput || !resultInput || !wrapper || !trigger || !panel) return;

            var options = Array.prototype.slice.call(panel.querySelectorAll('.calc-dropdown-option'));
            var selected = options.filter(function (o) { return o.classList.contains('is-selected'); })[0] || options[0];

            function currentPrice() {
                return selected ? parseFloat(selected.getAttribute('data-price')) || 0 : 0;
            }

            function formatNumber(value) {
                return isFinite(value) ? value.toLocaleString('en-US', { maximumFractionDigits: 8 }) : '';
            }

            function recalcFromAmount() {
                var amount = parseFloat(String(amountInput.value).replace(/,/g, ''));
                resultInput.value = isFinite(amount) ? formatNumber(amount * currentPrice()) : '';
            }

            function recalcFromResult() {
                var result = parseFloat(String(resultInput.value).replace(/,/g, ''));
                var price = currentPrice();
                amountInput.value = (isFinite(result) && price > 0) ? formatNumber(result / price) : '';
            }

            function openPanel() {
                panel.hidden = false;
                wrapper.classList.add('is-open');
                trigger.setAttribute('aria-expanded', 'true');
            }

            function closePanel() {
                panel.hidden = true;
                wrapper.classList.remove('is-open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            function selectOption(option) {
                if (selected) selected.classList.remove('is-selected');
                selected = option;
                selected.classList.add('is-selected');
                if (label) label.textContent = option.getAttribute('data-label');
                closePanel();
                recalcFromAmount();
            }

            trigger.addEventListener('click', function (e) {
                e.stopPropagation();
                panel.hidden ? openPanel() : closePanel();
            });

            options.forEach(function (option) {
                option.addEventListener('click', function () { selectOption(option); });
                option.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        selectOption(option);
                    }
                });
            });

            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) closePanel();
            });

            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closePanel();
            });

            amountInput.addEventListener('input', recalcFromAmount);
            resultInput.addEventListener('input', recalcFromResult);

            recalcFromAmount();
        })();
    </script>
    <!-- Team Section Starts -->
    <section class="team">
        <div class="container">
            <!-- Section Title Starts -->
            <div class="row text-center">
                <h2 class="title-head">our <span>experts</span></h2>
                <div class="title-head-subtitle">
                    <p>Our talented team of trading and markets experts</p>
                </div>
            </div>
            <!-- Section Title Ends -->
            <!-- Team Members Starts -->
            <div class="row team-content team-members">
                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                    <div class="team-member">
                        <img src="{{ asset('assets/bayya/images/team/member1.jpg') }}" class="img-responsive"
                            alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Lina Marzouki</h4>
                            <p>Ceo Founder</p>
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
                        <img src="{{ asset('assets/bayya/images/team/member2.jpg') }}" class="img-responsive"
                            alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Marco Verratti</h4>
                            <p>Director</p>
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
                        <img src="{{ asset('assets/bayya/images/team/member3.jpg') }}" class="img-responsive"
                            alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Emilia Bella</h4>
                            <p>Trading Consultant</p>
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
                        <img src="{{ asset('assets/bayya/images/team/member4.jpg') }}" class="img-responsive"
                            alt="team member">
                        <div class="team-member-caption social-icons">
                            <h4>Antonio Conte</h4>
                            <p>Platform Developer</p>
                            <ul class="list list-inline social">
                                <li><a href="#" class="fa fa-facebook"></a></li>
                                <li><a href="#" class="fa fa-twitter"></a></li>
                                <li><a href="#" class="fa fa-google-plus"></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Team Members Ends -->
        </div>
    </section>
    <!-- Team Section Ends -->
    <!-- CEO Quote Section Starts -->
    <section class="image-block2">
        <div class="container-fluid">
            <div class="row">
                <!-- Quote Starts -->
                <div class="col-xs-12 img-block-quote bg-image-2 cta-quote-full">
                    <blockquote>
                        <p>Crypto trading is one of the most important shifts in modern finance. For the first time
                            ever, anyone can send, receive or trade value with anyone else, anywhere on the planet,
                            conveniently and without restriction. It&rsquo;s the dawn of a better, more free world.</p>
                        <footer><img src="{{ asset('assets/bayya/images/ceo.jpg') }}" alt="ceo" /> <span>Marc
                                Smith</span> - CEO</footer>
                    </blockquote>
                </div>
                <!-- Quote Ends -->
            </div>
        </div>
    </section>
    <!-- CEO Quote Section Ends -->
    <!-- Blog Section Starts -->
    <section class="blog">
        <div class="container">
            <!-- Section Title Starts -->
            <div class="row text-center">
                <h2 class="title-head">Market <span>News</span></h2>
                <div class="title-head-subtitle">
                    <p>Live crypto headlines, syndicated from Cointelegraph</p>
                </div>
            </div>
            <!-- Section Title Ends -->
            <!-- Section Content Starts -->
            @if (count($headlines))
                <div class="row latest-posts-content">
                    @foreach ($headlines as $headline)
                        <div class="col-sm-4 col-md-4 col-xs-12">
                            <div class="latest-post">
                                <a href="{{ $headline['link'] }}" target="_blank" rel="noopener noreferrer">
                                    <img class="img-responsive"
                                        src="{{ $headline['image'] ?? asset('assets/bayya/images/blog/blog-post-small-1.jpg') }}"
                                        alt="{{ $headline['title'] }}">
                                </a>
                                <div class="post-body">
                                    <h4 class="post-title">
                                        <a href="{{ $headline['link'] }}" target="_blank" rel="noopener noreferrer">{{ $headline['title'] }}</a>
                                    </h4>
                                    <div class="post-text">
                                        <p>{{ $headline['excerpt'] }}</p>
                                    </div>
                                </div>
                                @if ($headline['published_at'])
                                    <div class="post-date">
                                        <span>{{ $headline['published_at']->format('d') }}</span>
                                        <span>{{ strtoupper($headline['published_at']->format('M')) }}</span>
                                    </div>
                                @endif
                                <a href="{{ $headline['link'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary">read more</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="row text-center">
                    <p>We couldn't load the latest market news right now &mdash; please check back shortly.</p>
                </div>
            @endif
            <!-- Section Content Ends -->
        </div>
    </section>
    <!-- Blog Section Ends -->
    <!-- Testimonials Section Starts -->
    <section class="testimonials-section">
        <div class="container">
            <div class="row text-center">
                <h2 class="title-head">What Our <span>Traders Say</span></h2>
                <div class="title-head-subtitle">
                    <p>Real feedback from the VelocityMarkets community</p>
                </div>
            </div>
        </div>
        <div class="testimonials-track-wrap">
            <div class="testimonials-track">
                @php
                    $testimonials = [
                        ['quote' => 'Copy trading on VelocityMarkets let me follow a top performer and see real results within a week. The dashboard makes it effortless.', 'name' => 'Daniel Reyes', 'role' => 'Retail Trader'],
                        ['quote' => 'Deposits are fast and the network options for USDT saved me a ton on fees. Support answered my questions within minutes.', 'name' => 'Amara Okafor', 'role' => 'Crypto Investor'],
                        ['quote' => 'I put a small amount into one of the investment plans just to test it, and the payouts have been right on schedule every time.', 'name' => 'Wei Zhang', 'role' => 'Investment Plan User'],
                        ['quote' => 'The trading interface is clean and fast. I moved from another exchange and haven&rsquo;t looked back since.', 'name' => 'Sofia Rossi', 'role' => 'Day Trader'],
                        ['quote' => 'What sold me was the transparency &mdash; every transaction, every payout, all visible in my dashboard. Exactly what I want from a platform.', 'name' => 'Marcus Bello', 'role' => 'Long-Term Holder'],
                    ];
                    $testimonials = array_merge($testimonials, $testimonials);
                @endphp
                @foreach ($testimonials as $t)
                    <div class="testimonial-card">
                        <p>&ldquo;{{ $t['quote'] }}&rdquo;</p>
                        <footer><strong>{{ $t['name'] }}</strong><span>{{ $t['role'] }}</span></footer>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Testimonials Section Ends -->
    <!-- FAQ Section Starts -->
    <section class="faq">
        <div class="container">
            <div class="row text-center">
                <h2 class="title-head">Frequently Asked <span>Questions</span></h2>
                <div class="title-head-subtitle">
                    <p>Quick answers before you get started</p>
                </div>
            </div>
            <div class="row" style="margin-top: 30px">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="panel-group" id="accordion-home">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion-home" href="#home-collapse1">
                                        What is VelocityMarkets?</a>
                                </h4>
                            </div>
                            <div id="home-collapse1" class="panel-collapse collapse in">
                                <div class="panel-body">VelocityMarkets is a crypto trading platform where you can trade
                                    spot markets yourself, copy the trades of experienced traders automatically, or
                                    place your funds into fixed-term investment plans that pay out a set return.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-home"
                                        href="#home-collapse2">
                                        Which cryptocurrencies can I deposit?</a>
                                </h4>
                            </div>
                            <div id="home-collapse2" class="panel-collapse collapse">
                                <div class="panel-body">You can deposit BTC, ETH and USDT. USDT deposits are supported
                                    over the TRC20, ERC20 and BEP20 networks so you can pick whichever is fastest and
                                    cheapest for you.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-home"
                                        href="#home-collapse3">
                                        How does copy trading work?</a>
                                </h4>
                            </div>
                            <div id="home-collapse3" class="panel-collapse collapse">
                                <div class="panel-body">Browse our list of verified traders, choose one whose strategy
                                    and risk level suit you, and allocate an amount to follow them. Their future trades
                                    are then mirrored on your account automatically.</div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a class="collapsed" data-toggle="collapse" data-parent="#accordion-home"
                                        href="#home-collapse4">
                                        Is my account secure?</a>
                                </h4>
                            </div>
                            <div id="home-collapse4" class="panel-collapse collapse">
                                <div class="panel-body">Yes. Deposits and withdrawals are reviewed before funds move,
                                    and your account is protected with strong authentication. We never share your data
                                    with third parties.</div>
                            </div>
                        </div>
                    </div>
                    <p class="text-center" style="margin-top: 20px">
                        <a href="{{ route('faq') }}" class="btn btn-primary">View All FAQs</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section Ends -->
    <!-- Call To Action Section Starts -->
    <section class="call-action-all">
        <div class="call-action-all-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- Call To Action Text Starts -->
                        <div class="action-text">
                            <h2>Get Started Today With VelocityMarkets</h2>
                            <p class="lead">Open account for free and start trading!</p>
                        </div>
                        <!-- Call To Action Text Ends -->
                        <!-- Call To Action Button Starts -->
                        <p class="action-btn"><a class="btn btn-primary" href="{{ route('register') }}">Register Now</a>
                        </p>
                        <!-- Call To Action Button Ends -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Call To Action Section Ends -->

</x-layouts.marketing>