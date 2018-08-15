<?php

namespace App\Http\Controllers;

use App\Recording;
use Illuminate\Http\Request;
use Google\Cloud\Speech\SpeechClient;

class HomeController extends Controller
{
    /**
     * @var string
     */
    protected $projectId = 'techtest-209407';

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $recordings = Recording::all();

        return view('home')->with(['recordings' => $recordings]);
    }
}
