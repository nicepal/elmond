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
                   
                
                    <div class="contact-card wow animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                        <h4 class="pb-5">Billing details</h4>
                        <form method="POST" autocomplete="off" class="verify-gcaptcha" action="{{ route('user.cart.guest.checkout') }}">
                            @csrf
                            <div class="row">
                                <!-- First Name -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="first_name" placeholder="First Name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="last_name" placeholder="Last Name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-lg-12">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="address" placeholder="Address" value="{{ old('address') }}" required>
                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Country -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <select class="form--control" name="country" required>
                                            <option value="">Select Country</option>
                                                @foreach($countries as $key => $country)
                                                    <option data-mobile_code="{{ $country->dial_code }}" value="{{ $country->country }}" data-code="{{ $key }}">{{ __($country->country) }}</option>
                                                @endforeach
                                        </select>
                                        @error('country')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- State -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                       
                                        <input type="text" class="form--control" name="state" placeholder="State *" value="{{ old('state') }}" required>
                                        @error('state')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="city" placeholder="City / Town *" value="{{ old('city') }}" required>
                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="phone" placeholder="Phone *" value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="password" class="form--control" name="password" placeholder="Password *" required>
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Confirm Password -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="password" class="form--control" name="confirmed" placeholder="Confirm Password *" required>
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-lg-12">
                                    <div class="mb-4 form-group">
                                        <input type="email" class="form--control" name="email" placeholder="Email address *" value="{{ old('email') }}" required>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                {{-- <div class="col-lg-12">
                                    <div class="mb-4 form-group">
                                        <button type="submit" class="btn btn-primary w-100">Checkout</button>
                                    </div>
                                </div> --}}
                            </div>
                        </form>

                    </div>
                @endif
            </div>
            @endif
            
            <!-- Right Side - Cart Items -->
            <div class="{{ auth()->user() ? 'col-lg-8' : 'col-lg-5' }} m-0">
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
                            
                            <!-- After the cart items list, update the totals section -->
                            <li class="px-4 py-3 list-group-item">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>Item Subtotal</div>
                                    <div class="fw-bold">₹{{ number_format((float)($subtotal ?? $total), 2) }}</div>
                                </div>
                                
                                @if(isset($coupon) && isset($discountAmount) && $discountAmount > 0)
                                <div class="d-flex align-items-center justify-content-between mb-2 text-success">
                                    <div>
                                        Coupon Discount ({{ $coupon['code'] }})
                                        <a href="{{ route('user.coupon.remove') }}" class="text-danger ms-2">
                                            <i class="fa fa-times-circle"></i>
                                        </a>
                                    </div>
                                    <div class="fw-bold">-₹{{ number_format((float)$discountAmount, 2) }}</div>
                                </div>
                                @endif
                                
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
                        @if(!isset($coupon))
                            <div class="col-lg-12 mt-5" style="margin-bottom:10px;">
                                <form method="post" action="{{ route('user.coupon.apply') }}" class="apply-coupon">
                                    @csrf
                                    <input type="text" name="coupon_code" placeholder="Enter Coupon Code..." class="form--control">
                                    <button class="btn btn-md" type="submit">Apply Coupon</button>
                                </form>
                                @if(session('error'))
                                    <div class="alert alert-danger mt-2">
                                        {{ session('error') }}
                                    </div>
                                @endif
                            </div>
                        @endif
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
$(document).ready(function() {
    @push('script')
    <script>
    $(document).ready(function() {
        $('.remove-from-cart-ajax').on('click', function() {
            var courseId = $(this).data('course-id');
            
            $.ajax({
                url: '{{ route("user.cart.remove", "") }}/' + courseId,
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    // Reload the page to show updated cart
                    window.location.reload();
                },
                error: function() {
                    alert('Error removing item from cart');
                }
            });
        });
    });
    </script>
    @endpush
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

<!-- Remove the duplicate coupon form that was here -->

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

