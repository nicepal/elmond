@php
    $newLaunchCourses = App\Models\Course::with('category', 'enrolls', 'reviews')
        ->where('launch_type', 'new_launch')
        ->where('admin_status', 1)
        ->where('status', 1)
        ->orderBy('launch_date', 'desc')
        ->take(6)
        ->get();
@endphp

@if($newLaunchCourses->count() > 0)
<section class="new-launch-courses pt-120">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-header text-center">
                    <h2 class="section-title">@lang('New Launch Courses')</h2>
                    <p class="section-desc">@lang('Discover our latest course offerings')</p>
                </div>
            </div>
        </div>
        
        <div class="row gy-4">
            @foreach($newLaunchCourses as $course)
                <div class="col-xxl-4 col-xl-4 col-lg-4 col-md-6">
                    <div class="base-card mb-4">
                        @if ($course->discount > 0)
                            <span class="dis-tag">-{{ $course->discount }}%</span>
                        @endif
                        <div class="launch-badge new-launch">
                            <i class="fas fa-rocket"></i> @lang('New Launch')
                        </div>
                        <div class="thumb-wrap">
                            <img src="{{ getImage(getFilePath('course_image') . '/' . $course->image) }}"
                                alt="course_image">
                        </div>
                        <div class="content-wrap">
                            <p class="category">{{ __(@$course->category->name) }}</p>
                            <a href="{{ route('course.details', [slug($course->name), $course->id]) }}">
                                <h6 class="title">{{ __(strLimit(@$course->name, 23)) }}</h6>
                            </a>
                            @if($course->launch_date)
                                <p class="launch-date new-launch-date">
                                    <i class="fas fa-calendar-alt"></i> 
                                    @lang('Launched'): {{ $course->launch_date->format('M d, Y') }}
                                </p>
                            @endif
                            <ul class="product-status">
                                <li>
                                    <i class="fa-solid fa-clock"></i>
                                    <p>{{ str_replace('ago', '', diffForHumans(@$course->created_at)) }}</p>
                                </li>
                                <li>
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    <p>{{ $course->enrolls->count() }} @lang('Students')</p>
                                </li>
                            </ul>
                        </div>
                        <div class="carn-btm">
                            <ul class="star-wrap rating-wrap">
                                @php
                                    $averageRatingHtml = calculateAverageRating($course->average_rating);
                                    if (!empty($averageRatingHtml['ratingHtml'])) {
                                        echo $averageRatingHtml['ratingHtml'];
                                    }
                                @endphp
                                <li>
                                    <p> {{ @$course->average_rating ?? 0 }}.0 ({{ @$course->review_count ?? 0 }})</p>
                                </li>
                            </ul>
                            <div class="price-wrap">
                                @if (@$course->discount > 0)
                                    <h6 class="price">
                                        {{ $general->cur_sym }}{{ priceCalculate(@$course->price, @$course->discount) }}
                                    </h6>
                                    <p class="dis-price">{{ $general->cur_sym }}{{ @$course->price }}</p>
                                @elseif(@$course->price == 0.0)
                                    <h6 class="price">@lang('Free')</h6>
                                @else
                                    <h6 class="price">{{ $general->cur_sym }}{{ @$course->price }}</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('course') }}?launch_type=new_launch" class="btn btn--base btn-lg">
                @lang('View All New Launch Courses') <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif