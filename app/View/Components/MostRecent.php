<?php

namespace App\View\Components;

use App\Services\LibraryService;
use Illuminate\View\Component;
use Illuminate\Support\Facades\App;

class MostRecent extends Component
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
        $recent = App::make(LibraryService::class)->findMostRecent();
        return view('components.videolist', ['entities' => $recent]);
    }
}
