<x-body>
    <x-videorecorder/>

    <x-most-recent>
        <x-slot:title>Najnowsze</x-slot>
    </x-most-recent>

    <x-popular>
        <x-slot:title>Popularne</x-slot>
    </x-popular>

    <x-slot:templates>

    <template id="tmplSelectMedia">
        <div class="select-media">
            <a href="#select-media" class="button-37 btn-select-media">Wybierz żródło wideo</a>
        </div>
    </template>

    <template id="tmplMediaControls">
        <div class="media-controls">
            <div class="recording-info"></div>
            <div class="control-button">
                <input id="btnRec" type="checkbox" />
                <label for="btnRec"></label>
            </div>
            <div class="recording-time">
                <time id="elapsed">00:00</time>
            </div>
        </div>
    </template>

    <template id="tmplRecordingInfo">
        <i class="recdot"></i><span>NAGRYWANIE</span>
    </template>

    <template id="tmplRecordingInfoStop">
        <span>ZATRZYMANO</span>
    </template>

    <template id="tmplNewVideo">
        <div class="new-video">
            <p class="message">Nagrano nowe wideo!</p>
            <div class="video-recorder-actions"></div>
        </div>
    </template>

    <template id="tmplUploadingBar">
        <div class="new-video">
            <p class="message">Wysyłanie wideo do biblioteki</p>
            <div class="uploading">
                <div class="uploadingBar"></div>
            </div>
        </div>
    </template>
    </x-slot>
    
    <x-slot:scripts>
        <script type="module" src="{{ asset('/js/recorder/index.js') }}"></script>
    </x-slot>

</x-body>
