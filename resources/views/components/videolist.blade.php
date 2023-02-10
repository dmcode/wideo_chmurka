<section class="block">
    <h2 class="title">{{ $title }}</h2>
    <ul class="video-list">
    @forelse ($entities as $item)
        <li>
            <a href="{{ route('public_video', $item->lid) }}">
                <div class="video-thumb">
                    <img src="{{ route('stream_thumb', $item->lid) }}" alt=""/>
                    <time class="duration">{{ duration($item->video->duration) }}</time>
                </div>
                <h3 class="title">{{ $item->title }}</h3>
                <div class="views">
                    <span>{{ $item->number_views }} wyświetleń</span>
                    <span>&diams;</span>
                    <time>{{ format_date($item->published_at) }}</time>
                </div>
            </a>
        </li>
    @empty
        <li><p class="empty-list">Tu jeszcze nic nie ma. Dodaj swoje wideo!</p></li>
    @endforelse
    </ul>
</section>