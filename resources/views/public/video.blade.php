<x-body>
    <x-slot:title>
        {{ $video->title }}
    </x-slot>
    <div class="block page-video">
        <div class="player-box">
            <div class="player">
                <video id="htmlplayer" controls
                       @if ($video->thumb) poster="{{ route('stream_thumb', $video->lid) }}" @endif
                       data-token="{{ csrf_token() }}"
                       data-vid="{{ $video->lid }}"
                       data-apirv="{{ route('register_view', $video->lid) }}">
                    <source src="{{ route('stream_video', $video->lid) }}" type="video/webm">
                </video>
            </div>
            <div class="video-info">
                <h2 class="title">{{ $video->title }}</h2>
                <div class="views">
                    <span>{{ $video->number_views }} wyświetleń</span>
                    <span>&diams;</span>
                    <span>opublikowano <time>{{ format_date($video->published_at) }}</time></span>
                </div>
                @if ($video->description)
                <p class="description">{{ $video->description }}</p>
                @endif
            </div>
        </div>
    </div>
    <x-slot:templates></x-slot>
    <x-slot:scripts>
        @vite(['resources/js/player.js'])
    </x-slot>
</x-body>