<section class="video-recorder">
    <div class="media-preview-wrapper"
         data-apiupload="{{ route('upload_blob') }}"
         @if(Auth::check()) data-auth="true" @endif>
        <video id="mediaPreview" autoplay loop muted poster="{{ asset('/images/loss-signal-poster.webp') }}">
            <source src="{{ asset('/video/loss-signal.webm') }}" type="video/webm">
        </video>
    </div>
    <div class="video-welcome">
        <h1>Nagraj swój pulpit i poślij go w chmurkę!</h1>
        @if(!Auth::check())
        <p class="message"><a href="{{ route('singup') }}">Załóż darmowe konto</a> aby zachować swoje nagranie.</p>
        @endif
    </div>
</section>
