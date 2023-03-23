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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $perpage = 10;
        $countOrders = MainRepository::getCountOrders();
        $paginator = $this->orderRepository->getAllOrders($perpage);

        MetaTag::setTags(['title' => 'Orders']);

        return view('blog.admin.order.index', [
            'countOrders' => $countOrders,
            'paginator' => $paginator
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * change status 0 or 1 in admin/orders/$id/edit
     */
    public function change($id)
    {
        $result = $this->orderRepository->changeStatusOrder($id);

        if ($result) {
            return redirect()
                ->route('blog.admin.orders.edit', $id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"]);
        }

    }

//    /** Change Status for Order */
//    public function changeStatusOrder($id)
//    {
//        $item = $this->getEditId($id);
//        if (!$item) {
//            abort(404);
//        }
//        $item->status = !empty($_GET['status']) ? '1' : '0';
//        $result = $item->update();
//        return $result;
//    }



//    /** Save Comment in Edit Order */
//    public function saveOrderComment($id)
//    {
//        $item = $this->getEditId($id);
//        if (!$item) {
//            abort(404);
//        }
//        $item->note = !empty($_POST['comment']) ? $_POST['comment'] : null;
//        $result = $item->update();
//        return $result;
//    }



//    /** Soft Delete one Order */
//    public function changeStatusOnDelete($id)
//    {
//        $item = $this->getEditId($id);
//        if (!$item) {
//            abort(404);
//        }
//        $item->status = '2';
//        $result = $item->update();
//        return $result;
//    }


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


}
