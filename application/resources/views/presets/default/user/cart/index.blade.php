@extends('presets.default.layouts.frontend')
@section('content')

<!-- ==================== Cart Section Start ==================== -->
<section class="container contact-section bg--white pb-100" style="margin-top:0px;">
    <div class="container">
        <div class="row get-in-touch justify-content-center gy-4">
            @if(!auth()->user())
            <!-- Left Side - Login and Billing Form (Only for non-authenticated users) -->
            <div class="col-lg-7">
                <div class="col-lg-12">
                    <div class="toggle_info"> 
                        <span><i class="fi-rs-user mr-10"></i><span class="text-muted font-lg">Already have an account?</span> 
                        <a href="#loginform" data-bs-toggle="collapse" class="font-lg collapsed" aria-expanded="false">Click here to login</a></span> 
                    </div>
                    <div class="panel-collapse login_form collapse" id="loginform">
                        <div class="panel-body">
                            <p class="mb-30 font-sm">If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing & Shipping section.</p>
                            <form method="post" action="{{ route('user.login') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-group mb-4">
                                            <input type="text" name="username" placeholder="Username Or Email" class="form--control" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <input type="password" name="password" placeholder="Password" class="form--control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="login_footer form-group">
                                    <div class="chek-form">
                                        <div class="custome-checkbox">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" value="1">
                                            <label class="form-check-label" for="remember"><span>Remember me</span></label>
                                        </div>
                                    </div>
                                    <a href="{{ route('user.password.request') }}">Forgot password?</a> 
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn--base-3" type="submit">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                @if(($count ?? 0) > 0)
                <div class="col-lg-12 mt-5">
                    <form method="post" class="apply-coupon">
                        @csrf
                        <input type="text" name="coupon_code" placeholder="Enter Coupon Code..." class="form--control">
                        <button class="btn btn-md" type="submit">Apply Coupon</button>
                    </form>
                </div>
                
                <div class="contact-card wow animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                    <h4 class="pb-5">Billing details</h4>
                    
                    {{-- Display validation errors --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form method="post" autocomplete="off" class="verify-gcaptcha" action="{{ route('user.cart.guest.checkout') }}">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="firstname" placeholder="First Name" value="{{ old('firstname') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Username <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="username" placeholder="Username" value="{{ old('username') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form--control" name="email" placeholder="Email Address" value="{{ old('email') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="number" class="form--control" name="mobile" placeholder="Mobile Number" value="{{ old('mobile') }}" required style="height: 45px;">
                                    <small class="text-muted">Country code: <span class="mobile-code">+1</span></small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Country <span class="text-danger">*</span></label>
                                    <select class="form--control" name="country" required style="height: 45px;">
                                        <option value="">Select Country</option>
                                        @if(isset($countries))
                                            @foreach($countries as $key => $country)
                                                <option data-mobile_code="{{ $country->dial_code }}" value="{{ $key }}" {{ old('country') == $key ? 'selected' : ($key == 'IN' ? 'selected' : '') }}>{{ $country->country }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form--control" name="password" placeholder="Password" required style="height: 45px;">
                                    <small class="text-muted">This password will be used for your account login</small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form--control" name="password_confirmation" placeholder="Confirm Password" required style="height: 45px;">
                                    <small class="text-muted">Please confirm your password</small>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Address <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="address" placeholder="Address" value="{{ old('address') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">City <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="city" placeholder="City" value="{{ old('city') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">State <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="state" placeholder="State" value="{{ old('state') }}" required style="height: 45px;">
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="mb-3 form-group">
                                    <label class="form-label">Zip Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form--control" name="zip" placeholder="Zip Code" value="{{ old('zip') }}" required style="height: 45px;">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @endif
            </div>
            @endif
            
            <!-- Right Side - Cart Items -->
            <div class="{{ auth()->user() ? 'col-lg-12' : 'col-lg-5' }} m-0">
                <div class="p-10 cart-totals" style="padding: 20px;">
                    <div class="d-flex align-items-end justify-content-between">
                        <h4>Your Order</h4>
                        @if(auth()->user())
                        <div class="text-muted">
                            Welcome, {{ auth()->user()->firstname }}!
                        </div>
                        @endif
                    </div>
                    <div class="divider-2 mb-30"><hr></div>
                    
                    @if(($count ?? 0) === 0)
                        <div class="text-center py-5">
                            <i class="fa-solid fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Your cart is empty</h5>
                            <p class="text-muted">Add some courses to get started!</p>
                            <a href="{{ route('course') }}" class="btn btn--base-3 mt-3">Browse Courses</a>
                        </div>
                    @else
                        <ul>
                            @foreach($items as $item)
                            <li class="px-4 py-3 list-group-item">
                                <div class="align-items-center row">
                                    <div class="col-md-1 col-1">
                                        <button type="button" class="btn btn-link p-0 text-danger remove-from-cart-ajax" data-course-id="{{ $item['id'] }}" onclick="return confirm('Remove this course from cart?')">
                                            <i class="fa fa-trash-can"></i>
                                        </button>
                                    </div>
                                    <div class="text-center text-muted col-md-2 col-2">
                                        <span>{{ (int)$item['qty'] }}</span>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <img src="{{ getImage(getFilePath('course_image') . '/' . ($item['image'] ?? 'default.jpg')) }}" 
                                             alt="Course" 
                                             class="img-fluid" 
                                             style="max-height: 60px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <div class="col-md-4 col-4">
                                        <h6 class="mb-0">{{ $item['title'] }}</h6>
                                        <span><small class="text-muted">QTY: {{ (int)$item['qty'] }}</small></span>
                                    </div>
                                    <div class="text-lg-end text-start text-md-end col-md-3 col-3">
                                        <span class="fw-bold">₹{{ number_format((float)$item['price'], 2) }}</span>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                            
                            <li class="px-4 py-3 list-group-item">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>Item Subtotal</div>
                                    <div class="fw-bold">₹{{ number_format((float)$total, 2) }}</div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>@18% IGST</div>
                                    <div class="fw-bold">₹{{ number_format((float)$total * 0.18, 2) }}</div>
                                </div>
                            </li>
                            
                            <div class="px-4 py-3 list-group-item">
                                <div class="d-flex align-items-center justify-content-between mb-2 fw-bold">
                                    <div>Grand Total</div>
                                    <div>₹{{ number_format((float)$total * 1.18, 2) }}</div>
                                </div>
                            </div>
                        </ul>
                        
                        @if(auth()->user())
                            <!-- Authenticated user - direct checkout -->
                            <form method="POST" action="{{ route('user.cart.checkout') }}">
                                @csrf
                                <button class="btn btn--base-3 w-100 mt-3">Proceed to Checkout</button>
                            </form>
                        @else
                            <!-- Guest user - submit billing form -->
                            <button class="btn btn--base-3 w-100 mt-3" onclick="document.querySelector('.verify-gcaptcha').submit()">Buy Now</button>
                        @endif
                        
                        <!-- <div class="mt-3 text-center">
                            <a href="{{ route('course') }}" class="btn btn-outline-secondary">Continue Shopping</a>
                        </div> -->
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==================== Cart Section End ==================== -->

@endsection

@push('style')
<style>
.cart-totals {
    border-radius: 15px;
    box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.05);
}

.apply-coupon {
    display: flex;
    gap: 0;
}

.apply-coupon input {
    height: 51px;
    border-radius: 10px 0 0 10px;
    background-image: url({{ asset('assets/presets/default/images/icons/coupon.png') }});
    background-position: 20px center;
    background-repeat: no-repeat;
    padding-left: 50px;
    border-right: none;
}

.apply-coupon button {
    min-width: 150px;
    height: 51px;
    border-radius: 0 10px 10px 0;
    background-color: #253D4E;
    border-left: none;
}

.apply-coupon button:hover {
    background-color: #3BB77E;
}

.login_form .panel-body {
    border: 1px solid #ececec;
    padding: 30px;
    margin-top: 30px;
    border-radius: 10px;
}

.login_footer {
    margin-bottom: 20px;
    margin-top: 5px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
}

.toggle_info {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.list-group-item {
    border-bottom: #eee solid 1px;
    border-left: none;
    border-right: none;
    border-top: none;
}

.list-group-item:first-child {
    border-top: none;
}

.contact-card {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    background: #fff;
    padding: 30px;
    margin-top: 30px;
}

@media (max-width: 768px) {
    .apply-coupon {
        flex-direction: column;
    }
    
    .apply-coupon input,
    .apply-coupon button {
        border-radius: 10px;
        border: 1px solid #ddd;
    }
    
    .apply-coupon button {
        margin-top: 10px;
    }
}
</style>
@endpush

@push('script')
<script>
"use strict";
$(document).ready(function() {
    // Country code functionality
    @if(isset($countries))
        $('select[name=country]').change(function(){
            $('input[name=mobile]').val('');
            var curText = $('select[name=country] :selected').data('mobile_code');
            $('.mobile-code').text('+'+curText);
        });
        $('select[name=country] :selected').trigger('change');
    @endif
    
    // Remove from cart functionality
    $('.remove-from-cart-ajax').on('click', function(e) {
        e.preventDefault();
        var courseId = $(this).data('course-id');
        
        $.ajax({
            url: '{{ route("user.cart.remove", "") }}/' + courseId,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Simply reload the page to refresh everything
                window.location.reload();
            },
            error: function() {
                alert('Error removing item from cart');
            }
        });
    });
    
    // Update cart count on page load
    updateCartCount();
    
    function updateCartCount() {
        $.get('{{ route("user.cart.count") }}', function(response) {
            var count = response.count || 0;
            $('#cart-count').text(count);
            
            if (count > 0) {
                $('#cart-count').removeClass('empty').show();
            } else {
                $('#cart-count').addClass('empty').hide();
            }
        }).fail(function() {
            console.log('Failed to update cart count');
        });
    }
});
</script>
@endpush

<script>
$(document).ready(function() {
    // Handle guest checkout form submission
    $('#guestCheckoutForm').on('submit', function() {
        const $btn = $('#buyNowBtn');
        const $btnText = $btn.find('.btn-text');
        const $btnSpinner = $btn.find('.btn-spinner');
        
        $btn.prop('disabled', true);
        $btnText.addClass('d-none');
        $btnSpinner.removeClass('d-none');
    });
});
</script>

<script>
// Function to recalculate cart totals
function recalculateCartTotals() {
    var subtotal = 0;
    $('.list-group-item').each(function() {
        var priceText = $(this).find('.fw-bold').text();
        var price = parseFloat(priceText.replace(/[^0-9.-]+/g, ''));
        if (!isNaN(price)) {
            subtotal += price;
        }
    });
    
    // Update subtotal display
    $('.subtotal-amount').text('{{ $general->cur_sym }}' + subtotal.toFixed(2));
    
    // Calculate tax and grand total (assuming 18% tax)
    var tax = (subtotal * 18) / 100;
    var grandTotal = subtotal + tax;
    
    $('.tax-amount').text('{{ $general->cur_sym }}' + tax.toFixed(2));
    $('.grand-total-amount').text('{{ $general->cur_sym }}' + grandTotal.toFixed(2));
    
    // Update any other total displays
    $('.total-amount').text('{{ $general->cur_sym }}' + grandTotal.toFixed(2));
}
</script>

