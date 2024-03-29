<x-body>
    @php
    $title = "Twoja biblioteka"
    @endphp
    <x-slot:title>
        {{ $title }}
    </x-slot>
    <div class="block library">
        <h1 class="page-title">{{ $title }}</h1>
        <ul class="video-list items">
            @forelse($entities as $item)
                <li>
                    <a href="{{ route('library_video', $item->lid) }}">
                        <div class="video-thumb">
                            <img src="{{ route('stream_thumb', $item->lid) }}" alt=""/>
                            <time class="duration">{{ duration($item->video->duration) }}</time>
                        </div>
                        <h3 class="title">{{ $item->title }}</h3>
                        <div class="views">
                            <span>{{ $item->number_views }} wyświetleń</span>
                            @if ($item->published_at)
                            <span>&diams;</span>
                            <time>{{ format_date($item->published_at) }}</time>
                            @endif
                        </div>
                        <div class="attrs">
                            <div class="visibility {{ $item->visibility }}">
                                {{ visibility($item->visibility) }}
                            </div>
                            <div class="res_codec">
                                <span>{{ $item->video->res_w }}x{{ $item->video->res_h }}</span>
                                <span class="codec">{{ $item->codec_name }}</span>
                            </div>
                        </div>
                    </a>
                </li>
            @empty
                <li><p class="empty-list">Tu jeszcze nic nie ma. Dodaj swoje wideo!</p></li>
            @endforelse
        </ul>
    </div>    

    <x-slot:templates></x-slot>
    <x-slot:scripts></x-slot>
</x-body>