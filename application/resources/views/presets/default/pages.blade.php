@extends($activeTemplate . 'layouts.frontend')

@section('content')
    <section class="contact-section bg--white pb-100">
        <div class="container">
            @if ($sections != null)
                @foreach (json_decode($sections) as $sec)
                    @include($activeTemplate . 'sections.' . $sec)
                @endforeach
            @endif
        </div>
    </section>
@endsection
