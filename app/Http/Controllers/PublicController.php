<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;


class PublicController extends BaseController
{
    /**
     * @return View
     */
    public function index()
    {
        return view('public.index');
    }
}
