@php
    $advertisementSection = getContent('advertisement.content', true);
@endphp
@extends($activeTemplate . 'layouts.frontend')
@section('content')
    <section class="all-course py-120">
        <div class="container">
            <div class="filter-box">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="m-0">@lang('What to learn next')</h5>
                    <div class="btn_wrap">
                        <button class="btn btn--base-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            <i class="fa-solid fa-sliders"></i></button>
                    </div>
                </div>
            </div>

            <div class="discover-box">
                <div class="row align-items-center">
                    <a href="#" class="close-btn"><i class="fa-solid fa-xmark"></i></a>
                    <div class="col-12 col-mb-12 col-lg-8">
                        <h6 class="title">
                            {{ __(@$advertisementSection->data_values?->title) }}
                        </h6>
                    </div>
                    <div class="col-12 col-mb-12 col-lg-4 text-md-start mt-3 mt-lg-0 text-lg-end">
                        <div>
                            <a href="{{ __(@$advertisementSection->data_values?->url) }}"
                                class="btn btn--base-3">@lang('Discover More')</a>
                        </div>
                    </div>
                </div>
            </div>

            <!--  -->
            <div class="mb-5 mt-5">
                <h6 class="title wow animate__ animate__fadeInUp animated mb-4" data-wow-delay="0.2s">
                    @lang('Short and sweet courses for you')</h6>
                <div class="row">
                    @forelse($courses as $item)
                        <div class="col-xxl-3 col-xl-4 col-lg-4 col-md-6">
                            <div class="base-card mb-5">
                                @if ($item->discount > 0)
                                    <span class="dis-tag">-{{ $item->discount }}%</span>
                                @endif
                                <div class="thumb-wrap">
                                    <img src="{{ getImage(getFilePath('course_image') . '/' . $item->image) }}"
                                        alt="course_image">
                                </div>
                                <div class="content-wrap">
                                    <p class="category">{{ __(@$item->category->name) }}</p>
                                    <a href="{{ route('course.details', [slug($item->name), $item->id]) }}">
                                        <h6 class="title">{{ __(strLimit(@$item->name, 23)) }}</h6>
                                    </a>
                                    <ul class="product-status">
                                        <li>
                                            <i class="fa-solid fa-clock"></i>
                                            <p>{{ str_replace('ago', '', diffForHumans(@$item->created_at)) }}</p>
                                        </li>
                                        <li>
                                            <i class="fa-solid fa-graduation-cap"></i>
                                            <p>{{ $item->enrolls->count() }} @lang('Students')</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="carn-btm">
                                    <ul class="star-wrap rating-wrap">
                                        @php
                                            $averageRatingHtml = calculateAverageRating($item->average_rating);
                                            if (!empty($averageRatingHtml['ratingHtml'])) {
                                                echo $averageRatingHtml['ratingHtml'];
                                            }
                                        @endphp

                                        <li>
                                            <p> {{ @$item->average_rating ?? 0 }}.0 ({{ @$item->review_count ?? 0 }})</p>
                                        </li>
                                    </ul>
                                    <div class="price-wrap">
                                        @if (@$item->discount > 0)
                                            <h6 class="price">
                                                {{ $general->cur_sym }}{{ priceCalculate(@$item->price, @$item->discount) }}
                                            </h6>
                                            <p class="dis-price">{{ $general->cur_sym }}{{ @$item->price }}</p>
                                        @elseif(@$item->price == 0.0)
                                            <h6 class="price">@lang('Free')</h6>
                                        @else
                                            <h6 class="price">{{ $general->cur_sym }}{{ @$item->price }}</h6>
                                        @endif
                                    </div>
                                </div>         
                                <!-- Add to Cart Button -->
                                <div class="cart-actions mt-3">
                                    @if (auth()->user() && $item->checkedPurchase())
                                        <span class="btn btn--success btn-sm w-100">@lang('Already Purchased')</span>
                                    @else
                                        <button class="btn btn--base btn-sm w-100 add-to-cart-btn" 
                                                data-course-id="{{ $item->id }}" 
                                                data-course-name="{{ $item->name }}">
                                            <i class="fas fa-shopping-cart me-1"></i> @lang('Add to Cart')
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <h5 class="text-center">@lang('No Course Found')</h5>
                    @endforelse
                </div>
                {{$courses->links()}}
            </div>
        </div>

        <!-- filter-modal -->
        <div class="modal fade modal-lg" id="exampleModal" tabindex="-1" 
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">@lang('Filter')</h5>
                        <div class="modal-btn-wrap">
                            <button data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('course.search')}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6>@lang('Search')</h6>
                                    <div class="categories-search from-group mb-3">
                                        <input class="form-check-input filter-by-category me-2 w-100  form--control" name="name"
                                            type="text" value="">
                                 
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6>@lang('Price')</h6>
                                    <div class="form--check">
                                        <input class="form-check-input filter-by-category" name="value"
                                            type="radio" value="0" id="free">
                                        <label class="form-check-label" for="free">
                                           @lang('Free')
                                        </label>
                                    </div>
                                    <div class="form--check mb-3">
                                        <input class="form-check-input filter-by-category" name="value"
                                            type="radio" value="1" id="premium">
                                        <label class="form-check-label" for="premium">
                                            @lang('Premium')
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <h6>@lang('Ratings')</h6>
                                    <div class="rating-wrap categories-search rating-stars ps-2 form--check mb-3">
                                        <input class="form-check-input filter-by-category me-2" name="review"
                                            type="radio" value="5" id="rating-5">
                                        <div>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                    <div class="rating-wrap categories-search rating-stars ps-2 form--check mb-3">
                                        <input class="form-check-input filter-by-category me-2" name="review"
                                            type="radio" value="4" id="rating-4">
                                        <div>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>

                                        </div>
                                    </div>

                                    <div class="rating-wrap categories-search rating-stars ps-2 form--check mb-3">
                                        <input class="form-check-input filter-by-category me-2" name="review"
                                            type="radio" value="3" id="rating-3">
                                        <div>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>

                                    <div class="rating-wrap categories-search rating-stars ps-2 form--check mb-3">
                                        <input class="form-check-input filter-by-category me-2" name="review"
                                            type="radio" value="2" id="rating-2">
                                        <div>
                                            <i class="fa-solid fa-star"></i>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>

                                    <div class="rating-wrap categories-search rating-stars ps-2 form--check mb-3">
                                        <input class="form-check-input filter-by-category me-2" name="review"
                                            type="radio" value="1" id="rating-1">
                                        <div>
                                            <i class="fa-solid fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <h6>@lang('Categories')</h6>
                                    @foreach ($categories as $category)
                                        <div class="form--check categories-search mb-2">
                                            <input type="radio" class="form-check-input filter-by-category"
                                                name="category" value="{{ $category->id }}"
                                                id="category{{ $category->id }}">
                                            <label for="category{{ $category->id }}"
                                                class="form-check-label">{{ __($category->name) }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                           
                            <div class="modal-footer">
                                <button type="reset" class="btn btn--base outline" data-bs-dismiss="modal">@lang('Clear')</button>
                                <button type="submit" class="btn btn--base">@lang('Show Course')</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('style')
    <style>
        .rating-comment-item .bottom ul {
            color: #ffc107;
        }
        .rating-wrap div {
            color: #ffc107;
        }
    </style>
@endpush

@push('scripts')
<script>
    $('.close-btn').on('click', function() {
        $('.discover-box').addClass('d-none');
    })
    
    // Add this script to course listing page
    $(document).ready(function() {
        // Check cart status for all courses on the page
        checkAllCoursesInCart();
        
        function checkAllCoursesInCart() {
            $.get('{{ route("user.cart.index") }}?json=1', function(response) {
                if (response.items) {
                    $('.add-to-cart-btn').each(function() {
                        var courseId = $(this).data('course-id');
                        var courseInCart = false;
                        
                        Object.keys(response.items).forEach(function(key) {
                            var item = response.items[key];
                            if (item.id == courseId) {
                                courseInCart = true;
                            }
                        });
                        
                        if (courseInCart) {
                            $(this).hide();
                            if ($(this).siblings('.remove-from-cart-btn').length === 0) {
                                $(this).after('<button class="btn btn--danger remove-from-cart-btn" data-course-id="' + courseId + '"><i class="fa-solid fa-minus me-1"></i> Remove from Cart</button>');
                            }
                        }
                    });
                }
            });
        }
        
        // ADD: Missing add to cart functionality
        $(document).on('click', '.add-to-cart-btn', function(e) {
            e.preventDefault();
            
            var courseId = $(this).data('course-id');
            var courseName = $(this).data('course-name');
            var button = $(this);
            
            // Disable button during request
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Adding...');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '{{ route("user.cart.add", ":course_id") }}'.replace(':course_id', courseId),
                type: 'POST',
                data: {
                    course_id: courseId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Course added to cart:', response);
                    
                    // Show success toast notification
                    if (response.message) {
                        showToast('success', response.message, courseName);
                    }
                    
                    // Refresh the page to show "Remove from Cart" button
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    console.error('Error adding to cart:', error);
                    console.log('Response:', xhr.responseText);
                    
                    // Re-enable button
                    button.prop('disabled', false).html('<i class="fas fa-shopping-cart me-1"></i> Add to Cart');
                    
                    if (xhr.status === 500) {
                        showToast('error', 'Server error. Please try again.', '');
                    } else {
                        showToast('error', 'Error adding course to cart. Please try again.', '');
                    }
                }
            });
        });
        
        // ADD: Remove from cart functionality
        $(document).on('click', '.remove-from-cart-btn', function(e) {
            e.preventDefault();
            
            var courseId = $(this).data('course-id');
            var button = $(this);
            
            // Disable button during request
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Removing...');
            
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            
            $.ajax({
                url: '{{ route("user.cart.remove", ":course_id") }}'.replace(':course_id', courseId),
                type: 'POST',
                data: {
                    course_id: courseId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    console.log('Course removed from cart:', response);
                    
                    // Show success message
                    if (response.message) {
                        showToast('success', response.message, '');
                    }
                    
                    // Refresh the page to show "Add to Cart" button
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr, status, error) {
                    console.error('Error removing from cart:', error);
                    
                    // Re-enable button
                    button.prop('disabled', false).html('<i class="fa-solid fa-minus me-1"></i> Remove from Cart');
                    showToast('error', 'Error removing course from cart. Please try again.', '');
                }
            });
        });
        
        // ADD: Toast notification function
        function showToast(type, message, courseName) {
            // You can implement your own toast notification here
            // For now, using a simple alert
            if (type === 'success') {
                alert('Success: ' + message + (courseName ? ' (' + courseName + ')' : ''));
            } else {
                alert('Error: ' + message);
            }
        }
    });
</script>
@endpush
