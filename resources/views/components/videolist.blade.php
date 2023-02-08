<section class="block">
    <h2 class="title">{{ $title }}</h2>
    <ul class="video-list">
    @foreach ($entities as $item)
        <li>
            <a href="">
                <div class="video-thumb">
                    <img src="" alt=""/>
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
    @endforeach
    @unless (empty($entities))
        <li><p class="empty-list">Tu jeszcze nic nie ma. Dodaj swoje wideo!</p></li>
    @endunless
    </ul>
</section>