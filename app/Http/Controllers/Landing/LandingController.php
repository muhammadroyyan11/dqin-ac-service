<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Show Home page
     */
    public function home()
    {
        return view('landing.home');
    }

    /**
     * Show About page
     */
    public function about()
    {
        return view('landing.about');
    }

    /**
     * Show Services page
     */
    public function services()
    {
        return view('landing.services');
    }

    /**
     * Show Contact page
     */
    public function contact()
    {
        return view('landing.contact');
    }
}
