<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use MetaTag;


class MainController extends Controller
{

    public function index()
    {
        MetaTag::setTags(['title' => 'Adnin Panel']);
        return view('blog.admin.main.index');
    }
}
