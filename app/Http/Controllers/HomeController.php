<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    // public function home()
    // {
    //     return view('home.index');
    // }
    public function home()
    {
        // dd(Auth::check());
        // dd(Auth::id());
        // dd(Auth::user());
        return view('home.index');
    }

    // public function contact()
    // {
    //     return view('home.contact');
    // }
    public function contact()
    {
        return view('home.contact');
    }

    public function secret()
    {
        return view('home.secret');
    }

    // public function blogPost($id, $welcome = 1)
    // {
    //     $pages = [
    //         1 => [
    //             'title' => 'from page 1',
    //         ],
    //         2 => [
    //             'title' => 'from page 2',
    //         ],
    //     ];
    //     $welcomes = [1 => '<b>Hello</b> ', 2 => 'Welcome to '];

    //     return view('blog-post', [
    //         'data' => $pages[$id],
    //         'welcome' => $welcomes[$welcome],
    //     ]);
    // }
}
