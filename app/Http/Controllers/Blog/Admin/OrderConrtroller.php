<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Admin\MainRepository;
use App\Repositories\Admin\OrderRepository;
use Illuminate\Http\Request;
use MetaTag;

class OrderConrtroller extends BlogAdminBaseController
{

    private $orderRepository;

    public function __construct()
    {
        parent::__construct();
        $this->orderRepository = app(OrderRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perpage = 5;
        $countOrders = MainRepository::getCountOrders();
        $paginator = $this->orderRepository->getAllOrders($perpage);

        MetaTag::setTags(['title' => 'Orders']);

        return view('blog.admin.order.index', [
            'countOrders' => $countOrders,
            'paginator' => $paginator
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = $this->orderRepository->getId($id);
        if(empty($item))
        {
            abort(404);
        }

        $order = $this->orderRepository->getOneOrder($item->id);
        if(empty($order))
        {
            abort(404);
        }

        $order_products = $this->orderRepository->getAllOrderProductsId($item->id);

        MetaTag::setTags(['title' => "Заказ № {$item->id}"]);

        return view('blog.admin.order.edit', [
            'item' => $item,
            'order' => $order,
            'order_products' => $order_products
           ]);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
