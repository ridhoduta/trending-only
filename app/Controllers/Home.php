<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        return view('penulis/artikel/index');
    }

    public function category(): string
    {
        return view('category');
    }

    public function contact(): string
    {
        return view('contact');
    }

    public function about(): string
    {
        return view('about');
    }

    public function author(): string
    {
        return view('author');
    }

    public function blog(): string
    {
        return view('blog-post');
    }
}
