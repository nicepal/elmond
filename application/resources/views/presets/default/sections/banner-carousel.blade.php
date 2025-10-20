@php
    $bannerCarouselElements = getContent('banner_carousel.element', false);
@endphp

<!-- Banner Carousel Section -->
<section class="hero banner-carousel-section position-absolute w-100">
    @if($bannerCarouselElements && count($bannerCarouselElements) > 1)
        <!-- Multiple banners - Show as carousel -->
        <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators">
                @foreach($bannerCarouselElements as $key => $element)
                    <button type="button" data-bs-target="#bannerCarousel" data-bs-slide-to="{{ $key }}" 
                        @if($key == 0) class="active" aria-current="true" @endif 
                        aria-label="Slide {{ $key + 1 }}"></button>
                @endforeach
            </div>
            
            <div class="carousel-inner">
                @foreach($bannerCarouselElements as $key => $element)
                    <div class="carousel-item @if($key == 0) active @endif">
                        <!-- Background Image with Overlay -->
                        <div class="banner-bg" style="background-image: url('{{ getImage('assets/images/frontend/banner_carousel/' . @$element->data_values->banner_image, '1920x1080') }}');">
                            <div class="banner-overlay"></div>
                            <div class="container h-100">
                                <div class="row h-100 align-items-center">
                                    <div class="col-lg-6 col-md-8">
                                        <div class="hero-content text-white">
                                            <h1 class="hero-title mb-4">{{ __(@$element->data_values->heading) }}</h1>
                                            <p class="hero-subtitle mb-4">{{ __(@$element->data_values->subheading) }}</p>
                                            <div class="hero-buttons">
                                                @if(@$element->data_values->primary_button_text && @$element->data_values->primary_button_link)
                                                    <a href="{{ @$element->data_values->primary_button_link }}" class="btn btn--primary me-3 mb-2">
                                                        {{ __(@$element->data_values->primary_button_text) }}
                                                    </a>
                                                @endif
                                                @if(@$element->data_values->secondary_button_text && @$element->data_values->secondary_button_link)
                                                    <a href="{{ @$element->data_values->secondary_button_link }}" class="btn btn--secondary mb-2">
                                                        {{ __(@$element->data_values->secondary_button_text) }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @elseif($bannerCarouselElements && count($bannerCarouselElements) == 1)
        <!-- Single banner - Show without carousel -->
        @php $element = $bannerCarouselElements->first(); @endphp
        <div class="banner-bg" style="background-image: url('{{ getImage('assets/images/frontend/banner_carousel/' . @$element->data_values->banner_image, '1920x1080') }}');">
            <div class="banner-overlay"></div>
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="hero-content text-white">
                            <h1 class="hero-title mb-4">{{ __(@$element->data_values->heading) }}</h1>
                            <p class="hero-subtitle mb-4">{{ __(@$element->data_values->subheading) }}</p>
                            <div class="hero-buttons">
                                @if(@$element->data_values->primary_button_text && @$element->data_values->primary_button_link)
                                    <a href="{{ @$element->data_values->primary_button_link }}" class="btn btn--primary me-3 mb-2">
                                        {{ __(@$element->data_values->primary_button_text) }}
                                    </a>
                                @endif
                                @if(@$element->data_values->secondary_button_text && @$element->data_values->secondary_button_link)
                                    <a href="{{ @$element->data_values->secondary_button_link }}" class="btn btn--secondary mb-2">
                                        {{ __(@$element->data_values->secondary_button_text) }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- No banners configured - Show placeholder -->
        <div class="banner-bg" style="background: linear-gradient(135deg, #{{ $general->base_color }} 0%, #{{ $general->secondary_color }} 100%);">
            <div class="banner-overlay"></div>
            <div class="container h-100">
                <div class="row h-100 align-items-center">
                    <div class="col-lg-6 col-md-8">
                        <div class="hero-content text-white">
                            <h1 class="hero-title mb-4">Welcome to Our Platform</h1>
                            <p class="hero-subtitle mb-4">Please configure your banner carousel from the admin panel.</p>
                            <div class="hero-buttons">
                                <a href="#" class="btn btn--primary me-3 mb-2">Get Started</a>
                                <a href="#" class="btn btn--secondary mb-2">Learn More</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</section>

<style>
.banner-carousel-section {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    min-height: 100vh;
    z-index: -1;
    overflow: hidden;
}

.banner-bg {
    min-height: 100vh;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    position: relative;
    display: flex;
    align-items: center;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    z-index: 1;
}

.hero-content {
    position: relative;
    z-index: 2;
    max-width: 600px;
    padding-top: 100px; /* Add padding to account for fixed header */
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.2;
    margin-bottom: 1.5rem;
}

.hero-subtitle {
    font-size: 1.25rem;
    line-height: 1.6;
    opacity: 0.9;
    margin-bottom: 2rem;
}

.hero-buttons .btn {
    padding: 12px 30px;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 50px;
    text-decoration: none;
    display: inline-block;
    transition: all 0.3s ease;
}

.btn--primary {
    background-color: #{{ $general->base_color }};
    border: 2px solid #{{ $general->base_color }};
    color: white;
}

.btn--primary:hover {
    background-color: transparent;
    color: #{{ $general->base_color }};
    transform: translateY(-2px);
}

.btn--secondary {
    background-color: transparent;
    border: 2px solid #{{ $general->secondary_color }};
    color: #{{ $general->secondary_color }};
}

.btn--secondary:hover {
    background-color: #{{ $general->secondary_color }};
    color: white;
    transform: translateY(-2px);
}

.carousel-indicators {
    bottom: 30px;
    z-index: 3;
}

.carousel-indicators button {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    background-color: transparent;
    opacity: 0.6;
}

.carousel-indicators button.active {
    background-color: #{{ $general->base_color }};
    opacity: 1;
}

.carousel-control-prev,
.carousel-control-next {
    width: 50px;
    height: 50px;
    background-color: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    z-index: 3;
}

.carousel-control-prev {
    left: 30px;
}

.carousel-control-next {
    right: 30px;
}

.carousel-control-prev:hover,
.carousel-control-next:hover {
    background-color: rgba(255, 255, 255, 0.3);
}

/* Ensure content appears above the banner */
body {
    position: relative;
    z-index: 1;
}

/* Add margin to main content to account for absolute positioned banner */
.main-content {
    margin-top: 100vh;
    position: relative;
    z-index: 2;
    background: white;
}

/* Responsive Design */
@media (max-width: 768px) {
    .hero-content {
        padding-top: 80px;
    }
    
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .hero-buttons .btn {
        padding: 10px 25px;
        font-size: 0.9rem;
        display: block;
        text-align: center;
        margin-bottom: 10px;
    }
    
    .carousel-control-prev,
    .carousel-control-next {
        width: 40px;
        height: 40px;
    }
    
    .carousel-control-prev {
        left: 15px;
    }
    
    .carousel-control-next {
        right: 15px;
    }
    
    .main-content {
        margin-top: 80vh;
    }
}

@media (max-width: 576px) {
    .hero-title {
        font-size: 2rem;
    }
    
    .banner-bg {
        min-height: 80vh;
    }
    
    .banner-carousel-section {
        min-height: 80vh;
    }
    
    .main-content {
        margin-top: 80vh;
    }
}
</style>
<!-- End Banner Carousel Section -->