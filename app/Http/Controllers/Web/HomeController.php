<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CarService;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(
        private CarService $carService
    ) {}

    /*Strona główna z listą samochodów*/
    public function index()
    {
        return view('home');
    }
}
