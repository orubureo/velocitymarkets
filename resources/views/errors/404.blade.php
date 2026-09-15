<x-layouts.marketing
    title="Page Not Found — VelocityMarkets"
    description="The page you're looking for doesn't exist."
>

    <!-- Banner Area Starts -->
    <section class="banner-area">
        <div class="banner-overlay">
            <div class="banner-text text-center">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-xs-12">
                            <h2 class="title-head">Page Not <span>Found</span></h2>
                            <hr>
                            <ul class="breadcrumb">
                                <li><a href="{{ route('home') }}">home</a></li>
                                <li>404</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Area Ends -->

    <!-- 404 Content Starts -->
    <section class="call-action-all">
        <div class="call-action-all-overlay">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="action-text text-center">
                            <h2>404</h2>
                            <p class="lead">Oops! The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.</p>
                        </div>
                        <p class="action-btn text-center"><a class="btn btn-primary" href="{{ route('home') }}">Back To Homepage</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- 404 Content Ends -->

</x-layouts.marketing>
