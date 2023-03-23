<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminOrderSaveRequest;
use App\Models\Admin\Order;
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

    public function save(AdminOrderSaveRequest $request, $id)
    {
        $result = $this->orderRepository->saveOrderComment($id);
        if ($result) {
            return redirect()
                ->route('blog.admin.orders.edit', $id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg' => "Ошибка сохранения"]);
        }
    }



    /**
     * Софт удаление
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $st = $this->orderRepository->changeStatusOnDelete($id);
        if ($st) {
            $result = Order::destroy($id);
            if ($result) {
                return redirect()
                    ->route('blog.admin.orders.index')
                    ->with(['success' => "Запись id [$id] удалена"]);
            } else {
                return back()->withErrors(['msg' => 'Ошибка удаления']);
            }
        } else {
            return back()->withErrors(['msg' => 'Статут не изменился']);
        }
    }



    /**
     * Полное удаление
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function forcedestroy($id)
    {
        if (empty($id)){
            return back()->withErrors(['msg' => 'Запись не найдена']);
        }

        $res = \DB::table('orders')
            ->delete($id);

        if ($res) {
            return redirect()
                ->route('blog.admin.orders.index')
                ->with(['success' => "Запись id [$id] удалена из БД"]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }
    }



}
