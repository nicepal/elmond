@extends($activeTemplate . 'layouts.frontend')
@section('content')

    {{-- Header section --}}
    @include($activeTemplate . 'sections.banner-carousel')

    <div class="main-content">
        @if ($sections->secs != null)
            @foreach (json_decode($sections->secs) as $sec)
                @include($activeTemplate . 'sections.' . $sec)
            @endforeach
        @endif
    </div>
@endsection
