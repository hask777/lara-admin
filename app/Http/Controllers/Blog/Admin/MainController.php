<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;
use App\Repositories\Admin\ProductRepository;
use Illuminate\Http\Request;

use MetaTag;


class MainController extends BlogAdminBaseController
{

    private $orderRepository;
    private $productRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = app(OrderRepository::class);
        $this->productRepository = app(ProductRepository::class);
    }

    public function index()
    {

        $countOrders = MainRepository::getCountOrders();
        $countUsers = MainRepository::getCountUsers();
        $countProducts = MainRepository::getCountProducts();
        $countCategories = MainRepository::getCategories();

        $perpage = 4;

        $last_orders = $this->orderRepository->getAllOrders($perpage);
        $last_products = $this->productRepository->getLastProducts($perpage);
//      dd($last_orders);

        MetaTag::setTags(['title' => 'Adnin Panel']);

        return view('blog.admin.main.index', [
            'countOrders' => $countOrders,
            'countUsers' => $countUsers,
            'countProducts' => $countProducts,
            'countCategories' => $countCategories,
            'last_orders' => $last_orders,
            'last_products' => $last_products
        ]);
    }
}
