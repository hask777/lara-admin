<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use MetaTag;

class MainController extends Controller
{

    // MetaTag::setTags()

    public function index()
    {
        return view('blog.admin.main.index');
    }
}
