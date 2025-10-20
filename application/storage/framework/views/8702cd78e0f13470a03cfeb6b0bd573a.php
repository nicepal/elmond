
<?php $__env->startSection('content'); ?>

<!-- ==================== Cart Section Start ==================== -->
<section class="container contact-section bg--white pb-100" style="margin-top:0px;">
    <div class="container">
        <div class="row get-in-touch justify-content-center gy-4">
            <?php if(!auth()->user()): ?>
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
                            <form method="post" action="<?php echo e(route('user.login')); ?>">
                                <?php echo csrf_field(); ?>
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
                                    <a href="<?php echo e(route('user.password.request')); ?>">Forgot password?</a> 
                                </div>
                                <div class="form-group mt-3">
                                    <button class="btn btn--base-3" type="submit">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <?php if(($count ?? 0) > 0): ?>
                   
                
                    <div class="contact-card wow animate__animated animate__fadeInUp" data-wow-delay="0.5s">
                        <h4 class="pb-5">Billing details</h4>
                        <form method="POST" autocomplete="off" class="verify-gcaptcha" action="<?php echo e(route('user.cart.guest.checkout')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="row">
                                <!-- First Name -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="first_name" placeholder="First Name" value="<?php echo e(old('first_name')); ?>" required>
                                        <?php $__errorArgs = ['first_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Last Name -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="last_name" placeholder="Last Name" value="<?php echo e(old('last_name')); ?>" required>
                                        <?php $__errorArgs = ['last_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Address -->
                                <div class="col-lg-12">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="address" placeholder="Address" value="<?php echo e(old('address')); ?>" required>
                                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Country -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <select class="form--control" name="country" required>
                                            <option value="">Select Country</option>
                                                <?php $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option data-mobile_code="<?php echo e($country->dial_code); ?>" value="<?php echo e($country->country); ?>" data-code="<?php echo e($key); ?>"><?php echo e(__($country->country)); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <?php $__errorArgs = ['country'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- State -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                       
                                        <input type="text" class="form--control" name="state" placeholder="State *" value="<?php echo e(old('state')); ?>" required>
                                        <?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- City -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="city" placeholder="City / Town *" value="<?php echo e(old('city')); ?>" required>
                                        <?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Phone -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="text" class="form--control" name="phone" placeholder="Phone *" value="<?php echo e(old('phone')); ?>" required>
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Password -->
                                <div class="col-lg-6">
                                    <div class="mb-4 form-group">
                                        <input type="password" class="form--control" name="password" placeholder="Password *" required>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                        <input type="email" class="form--control" name="email" placeholder="Email address *" value="<?php echo e(old('email')); ?>" required>
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <small class="text-danger"><?php echo e($message); ?></small>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                
                            </div>
                        </form>

                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <!-- Right Side - Cart Items -->
            <div class="<?php echo e(auth()->user() ? 'col-lg-8' : 'col-lg-5'); ?> m-0">
                <div class="p-10 cart-totals" style="padding: 20px;">
                    <div class="d-flex align-items-end justify-content-between">
                        <h4>Your Order</h4>
                        <?php if(auth()->user()): ?>
                        <div class="text-muted">
                            Welcome, <?php echo e(auth()->user()->firstname); ?>!
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="divider-2 mb-30"><hr></div>
                    
                    <?php if(($count ?? 0) === 0): ?>
                        <div class="text-center py-5">
                            <i class="fa-solid fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Your cart is empty</h5>
                            <p class="text-muted">Add some courses to get started!</p>
                            <a href="<?php echo e(route('course')); ?>" class="btn btn--base-3 mt-3">Browse Courses</a>
                        </div>
                    <?php else: ?>
                        <ul>
                            <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="px-4 py-3 list-group-item">
                                <div class="align-items-center row">
                                    <div class="col-md-1 col-1">
                                        <button type="button" class="btn btn-link p-0 text-danger remove-from-cart-ajax" data-course-id="<?php echo e($item['id']); ?>" onclick="return confirm('Remove this course from cart?')">
                                            <i class="fa fa-trash-can"></i>
                                        </button>
                                    </div>
                                    <div class="text-center text-muted col-md-2 col-2">
                                        <span><?php echo e((int)$item['qty']); ?></span>
                                    </div>
                                    <div class="col-md-2 col-2">
                                        <img src="<?php echo e(getImage(getFilePath('course_image') . '/' . ($item['image'] ?? 'default.jpg'))); ?>" 
                                             alt="Course" 
                                             class="img-fluid" 
                                             style="max-height: 60px; object-fit: cover; border-radius: 8px;">
                                    </div>
                                    <div class="col-md-4 col-4">
                                        <h6 class="mb-0"><?php echo e($item['title']); ?></h6>
                                        <span><small class="text-muted">QTY: <?php echo e((int)$item['qty']); ?></small></span>
                                    </div>
                                    <div class="text-lg-end text-start text-md-end col-md-3 col-3">
                                        <span class="fw-bold">₹<?php echo e(number_format((float)$item['price'], 2)); ?></span>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            
                            <!-- After the cart items list, update the totals section -->
                            <li class="px-4 py-3 list-group-item">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>Item Subtotal</div>
                                    <div class="fw-bold">₹<?php echo e(number_format((float)($subtotal ?? $total), 2)); ?></div>
                                </div>
                                
                                <?php if(isset($coupon) && isset($discountAmount) && $discountAmount > 0): ?>
                                <div class="d-flex align-items-center justify-content-between mb-2 text-success">
                                    <div>
                                        Coupon Discount (<?php echo e($coupon['code']); ?>)
                                        <a href="<?php echo e(route('user.coupon.remove')); ?>" class="text-danger ms-2">
                                            <i class="fa fa-times-circle"></i>
                                        </a>
                                    </div>
                                    <div class="fw-bold">-₹<?php echo e(number_format((float)$discountAmount, 2)); ?></div>
                                </div>
                                <?php endif; ?>
                                
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <div>@18% IGST</div>
                                    <div class="fw-bold">₹<?php echo e(number_format((float)$total * 0.18, 2)); ?></div>
                                </div>
                            </li>
                            
                            <div class="px-4 py-3 list-group-item">
                                <div class="d-flex align-items-center justify-content-between mb-2 fw-bold">
                                    <div>Grand Total</div>
                                    <div>₹<?php echo e(number_format((float)$total * 1.18, 2)); ?></div>
                                </div>
                            </div>
                        </ul>
                        <?php if(!isset($coupon)): ?>
                            <div class="col-lg-12 mt-5" style="margin-bottom:10px;">
                                <form method="post" action="<?php echo e(route('user.coupon.apply')); ?>" class="apply-coupon">
                                    <?php echo csrf_field(); ?>
                                    <input type="text" name="coupon_code" placeholder="Enter Coupon Code..." class="form--control">
                                    <button class="btn btn-md" type="submit">Apply Coupon</button>
                                </form>
                                <?php if(session('error')): ?>
                                    <div class="alert alert-danger mt-2">
                                        <?php echo e(session('error')); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        <?php if(auth()->user()): ?>
                            <!-- Authenticated user - direct checkout -->
                            <form method="POST" action="<?php echo e(route('user.cart.checkout')); ?>">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn--base-3 w-100 mt-3">Proceed to Checkout</button>
                            </form>
                        <?php else: ?>
                            <!-- Guest user - submit billing form -->
                            <button class="btn btn--base-3 w-100 mt-3" onclick="document.querySelector('.verify-gcaptcha').submit()">Buy Now</button>
                        <?php endif; ?>
                        
                        <!-- <div class="mt-3 text-center">
                            <a href="<?php echo e(route('course')); ?>" class="btn btn-outline-secondary">Continue Shopping</a>
                        </div> -->
                    <?php endif; ?>
                </div>
            </div>
          
        </div>
    </div>
</section>
<!-- ==================== Cart Section End ==================== -->

<?php $__env->stopSection(); ?>

<?php $__env->startPush('style'); ?>
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
    background-image: url(<?php echo e(asset('assets/presets/default/images/icons/coupon.png')); ?>);
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('script'); ?>
<script>
$(document).ready(function() {
    <?php $__env->startPush('script'); ?>
    <script>
    $(document).ready(function() {
        $('.remove-from-cart-ajax').on('click', function() {
            var courseId = $(this).data('course-id');
            
            $.ajax({
                url: '<?php echo e(route("user.cart.remove", "")); ?>/' + courseId,
                type: 'POST',
                data: {
                    _token: '<?php echo e(csrf_token()); ?>'
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
    <?php $__env->stopPush(); ?>
    // Update cart count on page load
    updateCartCount();
    
    function updateCartCount() {
        $.get('<?php echo e(route("user.cart.count")); ?>', function(response) {
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

<?php $__env->stopPush(); ?>

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


<?php echo $__env->make('presets.default.layouts.frontend', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/elmond/application/resources/views/user/cart/index.blade.php ENDPATH**/ ?>