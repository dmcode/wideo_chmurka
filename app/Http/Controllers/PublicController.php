<?php

namespace App\Http\Controllers;

use App\Services\LibraryService;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Session;


class PublicController extends BaseController
{
    use VideoTrait;

    /**
     * @return View
     */
    public function index()
    {
        return view('public.index');
    }

    public function video(Request $request, $lid, LibraryService $library)
    {
        try{
            $entity = $this->getVideo($lid);
            Session::put($entity->lid, true);
            return view('public.video', ['video' => $entity]);
        }
        catch (\InvalidArgumentException) {
            abort(404);
        }
    }
}
