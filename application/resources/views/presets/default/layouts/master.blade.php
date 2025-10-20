<!doctype html>
<html lang="{{ config('app.locale') }}" itemscope itemtype="http://schema.org/WebPage">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> {{ $general->siteName(__($pageTitle)) }}</title>

    @include('includes.seo')

    <link href="{{ asset('assets/common/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/common/css/all.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/common/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/custom.css') }}">

    <!-- Magnific Popup CSS-->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/magnific-popup.css') }}">
    <!-- Slick CSS-->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/slick.css') }}">
    <!-- Odometer CSS-->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/odometer.css') }}">
    <!-- Main CSS -->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/main.css') }}">
    <!-- animate CSS-->
    <link rel="stylesheet" href="{{ asset($activeTemplateTrue . 'css/animate.min.css') }}">


    @stack('style-lib')
    @stack('style')

    <link rel="stylesheet"
        href="{{ asset($activeTemplateTrue . 'css/color.php') }}?color1={{ $general->base_color }}&color2={{ $general->secondary_color }}">
</head>

<body>


    <!--==================== Preloader Start ====================-->
    <div id="loading">
        <div id="loading-center">
            <div id="loading-center-absolute">
                <span class="loader"></span>
            </div>
        </div>
    </div>

    <section class="dashboard-section">
        <div class="dashboard">
            @include($activeTemplate . 'components.user.sidebar')
            <!-- dashboard side bar /> -->
            <div class="dashboard-container-wrap">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="dashboard-body">
                                <!-- < dashboard header -->
                                @include($activeTemplate . 'components.user.navbar')
                                <!-- dashboard header /> -->
                                <div>
                                    @yield('content')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="{{ asset('assets/common/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/common/js/bootstrap.bundle.min.js') }}"></script>
    <!-- apexcharts js -->
    <script src="{{ asset($activeTemplateTrue . 'js/apexcharts.min.js') }}"></script>
    <!-- Slick js -->
    <script src="{{ asset($activeTemplateTrue . 'js/slick.min.js') }}"></script>
    <!-- Magnific Popup js -->
    <script src="{{ asset($activeTemplateTrue . 'js/jquery.magnific-popup.min.js') }}"></script>
    <!-- Odometer js -->
    <script src="{{ asset($activeTemplateTrue . 'js/odometer.min.js') }}"></script>
    <!-- Viewport js -->
    <script src="{{ asset($activeTemplateTrue . 'js/viewport.jquery.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset($activeTemplateTrue . 'js/main.js') }}"></script>
    <!-- wow js -->
    <script src="{{ asset($activeTemplateTrue . 'js/wow.min.js') }}"></script>
    <!-- moment js -->
    <script src="{{ asset($activeTemplateTrue . 'js/moment.min.js') }}"></script>


    @stack('script-lib')
    @include('includes.notify')
    @include('includes.plugins')
    @stack('script')

    <script>
        (function($) {
            "use strict";
            $(".langSel").on("change", function() {
                window.location.href = "{{ route('home') }}/change/" + $(this).val();
            });

        })(jQuery);
    </script>

    <script>
        (function($) {
            "use strict";

            $('form').on('submit', function() {
                if ($(this).valid()) {
                    $(':submit', this).attr('disabled', 'disabled');
                }
            });

            var inputElements = $('[type=text],[type=password],select,textarea');
            $.each(inputElements, function(index, element) {
                element = $(element);
                element.closest('.form-group').find('label').attr('for', element.attr('name'));
                element.attr('id', element.attr('name'))
            });

            $.each($('input, select, textarea'), function(i, element) {

                if (element.hasAttribute('required')) {
                    $(element).closest('.form-group').find('label').addClass('required');
                }

            });


            $('.showFilterBtn').on('click', function() {
                $('.responsive-filter-card').slideToggle();
            });


            let headings = $('.table th');
            let rows = $('.table tbody tr');
            let columns
            let dataLabel;

            $.each(rows, function(index, element) {
                columns = element.children;
                if (columns.length == headings.length) {
                    $.each(columns, function(i, td) {
                        dataLabel = headings[i].innerText;
                        $(td).attr('data-label', dataLabel)
                    });
                }
            });

       
        })(jQuery);
    </script>

    <!-- WhatsApp Chat Widget -->
    @if($general->whatsapp_enabled)
    <div class="whatsapp-chat-widget">
        <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $general->whatsapp_number) }}?text={{ urlencode($general->whatsapp_message ?? 'Hello! How can we help you?') }}" 
           target="_blank" class="whatsapp-btn" title="Chat with us on WhatsApp">
            <i class="fab fa-whatsapp"></i>
        </a>
    </div>
    @endif

</body>

</html>

<style>
    .whatsapp-chat-widget {
        position: fixed;
        bottom: 20px;
        left: 20px;  /* Changed to left for bottom-left positioning */
        z-index: 999999999999999;  /* Increased z-index to ensure it's on top */
    }
    
    .whatsapp-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        background-color: #25D366;
        border-radius: 50%;
        color: white !important;  /* Added !important */
        font-size: 30px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        transition: all 0.3s ease;
        animation: pulse 2s infinite;
    }
    
    .whatsapp-btn:hover {
        background-color: #128C7E;
        transform: scale(1.1);
        color: white !important;  /* Added !important */
        text-decoration: none;
    }
    
    .whatsapp-btn i {
        font-size: 30px;
        line-height: 1;
    }
    
    @keyframes pulse {
        0% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
        }
        70% {
            box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
        }
    }
    
    @media (max-width: 768px) {
        .whatsapp-chat-widget {
            bottom: 15px;
            left: 15px;  /* Changed to left for mobile too */
        }
        
        .whatsapp-btn {
            width: 50px;
            height: 50px;
            font-size: 24px;
        }
        
        .whatsapp-btn i {
            font-size: 24px;
        }
    }
</style>


@if($general->whatsapp_enabled && $general->whatsapp_number)
<div class="whatsapp-chat-widget">
    <a href="https://wa.me/{{ str_replace(['+', ' ', '-'], '', $general->whatsapp_number) }}?text={{ urlencode($general->whatsapp_message ?? 'Hello! How can we help you?') }}" 
       target="_blank" class="whatsapp-btn" title="Chat with us on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>
@endif

<style>
.whatsapp-chat-widget {
    position: fixed;
    bottom: 20px;
    left: 20px;
    z-index: 999999;
}

.whatsapp-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 60px;
    height: 60px;
    background-color: #25D366;
    border-radius: 50%;
    color: white !important;
    font-size: 30px;
    text-decoration: none;
    box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
    transition: all 0.3s ease;
    animation: pulse 2s infinite;
}

.whatsapp-btn:hover {
    background-color: #128C7E;
    transform: scale(1.1);
    color: white !important;
    text-decoration: none;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(37, 211, 102, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(37, 211, 102, 0);
    }
}

@media (max-width: 768px) {
    .whatsapp-chat-widget {
        bottom: 15px;
        left: 15px;
    }
    
    .whatsapp-btn {
        width: 50px;
        height: 50px;
        font-size: 24px;
    }
}
</style>
