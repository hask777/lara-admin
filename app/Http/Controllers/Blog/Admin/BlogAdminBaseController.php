<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Blog\BaseController as MainBaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BlogAdminBaseController extends MainBaseController
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('status');

    }
}
