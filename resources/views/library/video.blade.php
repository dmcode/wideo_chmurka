<x-body>
    @php
    $title = "Szczegóły wideo"
    @endphp
    <x-slot:title>
        {{ $title }}
    </x-slot>
    <div class="block library">
        <a href="{{ route('library') }}" class="btn-back"><i class="fa-solid fa-circle-chevron-left"></i> Wszystkie filmy</a>

        <h1 class="page-title">{{ $title }}</h1>

        <div class="video-editor">
            <div class="player">
                <video controls
                       @if ($item->thumb) poster="{{ route('stream_thumb', $item->lid) }}" @endif
                       >
                    <source src="{{ route('stream_video', $item->lid) }}" type="video/webm">
                </video>
                <div class="video-info">
                    <div class="views">
                        <span>{{ $item->number_views }} wyświetleń</span>
                    </div>
                    <div class="attrs">
                        <div class="res_codec">
                            <span>rozdzielczość: {{ $item->video->res_w }}x{{ $item->video->res_h }}</span>
                            <span class="codec">kodek: {{ $item->video->codec_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="video-form">
                <form id="videoForm" method="post"
                      data-lid="{{ $item->lid }}" data-apivd="{{ route('video_data') }}" data-token="{{ csrf_token() }}"
                      data-library="{{ route('library') }}">
                    <div class="row">
                        <label for="title">Tytuł wideo</label>
                        <input id="title" type="text" name="title" required maxlength="100" value="{{ $item->title }}" />
                        <p class="tip">Maks. 100 znaków</p>
                    </div>
                    <div class="row">
                        <label for="description">Opis</label>
                        <textarea id="description" name="description" maxlength="1000" rows="5">{{ $item->description }}</textarea>
                        <p class="tip">Maks. 1000 znaków</p>
                    </div>
                    <div class="row">
                        <label>Publiczny</label>
                        <label class="published">
                            <input type="checkbox" id="visibility" name="visibility" @if ($item->visibility == 'public') checked @endif >
                            <span class="slider"></span>
                        </label>
                    </div>
                    <div class="actions">
                        <span class="results"></span>
                        <span>
                            <input type="button" id="btnDelete" class="btn-delete" value="Usuń"/>
                            <input type="submit" class="btn-save" value="Zapisz"/>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot:templates></x-slot>
    <x-slot:scripts>
        <script type="module" src="{{ asset('/js/editor.js') }}"></script>
    </x-slot>
</x-body>