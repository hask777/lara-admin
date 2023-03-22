<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MainRepository;
use Illuminate\Http\Request;

use MetaTag;


class MainController extends Controller
{

    public function index()
    {

        $countOrders = MainRepository::getCountOrders();
        $countUsers = MainRepository::getCountUsers();
        $countProducts = MainRepository::getCountProducts();
        $countCategories = MainRepository::getCategories();


        MetaTag::setTags(['title' => 'Adnin Panel']);
        return view('blog.admin.main.index', [
            'countOrders' => $countOrders,
            'countUsers' => $countUsers,
            'countProducts' => $countProducts,
            'countCategories' => $countCategories
        ]);
    }
}
