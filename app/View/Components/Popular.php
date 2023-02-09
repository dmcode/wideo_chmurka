<?php

namespace App\View\Components;

use App\Services\LibraryService;
use Illuminate\View\Component;
use Illuminate\Support\Facades\App;

class Popular extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $popular = App::make(LibraryService::class)->findPopular();
        return view('components.videolist', ['entities' => $popular]);
    }
}
