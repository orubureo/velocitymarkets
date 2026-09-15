<div>

    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="banner-overlay">
            <div class="banner-text text-center">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h2 class="title-head">Get in <span>touch</span></h2>
                            <hr>
                            <ul class="breadcrumb">
                                <li><a href="{{ route('home') }}"> home</a></li>
                                <li>contact</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area Ends -->

    <!-- Contact Section Starts -->
    <section class="contact">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 contact-form">
                    <h3 class="col-xs-12">feel free to drop us a message</h3>
                    <p class="col-xs-12">Questions about your account, a partnership, or feedback on the platform? Send us a message using the form below and our team will get back to you.</p>

                    @if ($sent)
                        <!-- Success Message Starts -->
                        <div class="col-xs-12">
                            <div class="alert alert-success" role="alert">
                                Thanks &mdash; we&rsquo;ll get back to you soon.
                            </div>
                        </div>
                        <!-- Success Message Ends -->
                    @else
                        <!-- Contact Form Starts -->
                        <form class="form-contact">
                            <!-- Input Field Starts -->
                            <div class="form-group col-md-6">
                                <input class="form-control" wire:model="name" id="name" placeholder="NAME" type="text">
                                @error('name') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Input Field Ends -->
                            <!-- Input Field Starts -->
                            <div class="form-group col-md-6">
                                <input class="form-control" wire:model="email" id="email" placeholder="EMAIL" type="email">
                                @error('email') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Input Field Ends -->
                            <!-- Input Field Starts -->
                            <div class="form-group col-xs-12">
                                <input class="form-control" wire:model="subject" id="subject" placeholder="SUBJECT" type="text">
                                @error('subject') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Input Field Ends -->
                            <!-- Input Field Starts -->
                            <div class="form-group col-xs-12">
                                <textarea class="form-control" wire:model="message" id="message" placeholder="MESSAGE"></textarea>
                                @error('message') <span class="help-block">{{ $message }}</span> @enderror
                            </div>
                            <!-- Input Field Ends -->
                            <!-- Submit Form Button Starts -->
                            <div class="form-group col-xs-12 col-sm-4">
                                <button class="btn btn-primary btn-contact" type="button" wire:click="submit" wire:loading.attr="disabled" wire:target="submit">
                                    <span wire:loading.remove wire:target="submit">send message</span>
                                    <span wire:loading wire:target="submit">sending&hellip;</span>
                                </button>
                            </div>
                            <!-- Submit Form Button Ends -->
                        </form>
                        <!-- Contact Form Ends -->
                    @endif
                </div>
                <!-- Contact Widget Starts -->
                <div class="col-xs-12 col-md-4">
                    <div class="widget">
                        <div class="contact-page-info">
                            <!-- Contact Info Box Starts -->
                            <div class="contact-info-box">
                                <i class="fa fa-envelope big-icon"></i>
                                <div class="contact-info-box-content">
                                    <h4>Email</h4>
                                    <p>support@velocitymarkets.test</p>
                                </div>
                            </div>
                            <!-- Contact Info Box Ends -->
                            <!-- Contact Info Box Starts -->
                            <div class="contact-info-box">
                                <i class="fa fa-clock-o big-icon"></i>
                                <div class="contact-info-box-content">
                                    <h4>Support Hours</h4>
                                    <p>Mon &ndash; Fri, 9am &ndash; 6pm (UTC)</p>
                                </div>
                            </div>
                            <!-- Contact Info Box Ends -->
                            <!-- Social Media Icons Starts -->
                            <div class="contact-info-box">
                                <i class="fa fa-share-alt big-icon"></i>
                                <div class="contact-info-box-content">
                                    <h4>Social Profiles</h4>
                                    <div class="social-contact">
                                        <ul>
                                            <li class="facebook"><a href="#" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                            <li class="twitter"><a href="#" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                            <li class="google-plus"><a href="#" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Social Media Icons Ends -->
                        </div>
                    </div>
                </div>
                <!-- Contact Widget Ends -->
            </div>
        </div>
    </section>
    <!-- Contact Section Ends -->

    <!-- Call To Action Section Starts -->
    <section class="call-action-all">
        <div class="call-action-all-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <!-- Call To Action Text Starts -->
                        <div class="action-text">
                            <h2>Get Started Today With VelocityMarkets</h2>
                            <p class="lead">Open a free account and start trading, copy trading, or investing today!</p>
                        </div>
                        <!-- Call To Action Text Ends -->
                        <!-- Call To Action Button Starts -->
                        <p class="action-btn"><a class="btn btn-primary" href="{{ route('register') }}">Register Now</a></p>
                        <!-- Call To Action Button Ends -->
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Call To Action Section Ends -->

</div>
