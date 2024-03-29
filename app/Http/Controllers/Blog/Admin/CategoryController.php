<?php

namespace App\Http\Controllers\Blog\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\Admin\Category;
use App\Repositories\Admin\CategoryRepository;
use Illuminate\Http\Request;
use MetaTag;


class CategoryController extends BlogAdminBaseController
{
    private $categoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->categoryRepository = app(CategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $arrMenu = Category::all();
        $menu = $this->categoryRepository->buildMenu($arrMenu);


        MetaTag::setTags(['title' => 'Category']);
        return view('blog.admin.category.index', [
            'menu' => $menu,
        ]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $item = new Category();
        $categoryList = $this->categoryRepository->getComboBoxCategories();

        MetaTag::setTags(['title' => 'Category create']);

        return view('blog.admin.category.create', [
            'categories' => Category::with('children')->where('parent_id', '0')->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */

    public function store(BlogCategoryUpdateRequest $request)
    {
        $name = $this->categoryRepository->checkUniqueName($request->title,$request->parent_id);

        if($name){
            return back()
                ->withErrors(['msg'=>'Не может быть в одной и той же Категории двух одинаковых. Выбирите другую Категорию.'])
                ->withInput();
        }

        $data = $request->input();
        $item = new Category();
        $item->fill($data)->save();

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.create', [$item->id])
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()
                ->withErrors(['msg'=>'Ошибка сохранения'])
                ->withInput();
        }
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit($id, CategoryRepository $categoryRepository)
    {
        $item = $this->categoryRepository->getId($id);
        if (empty($item)){
            abort(404);
        }

        $categoryList = $this->categoryRepository->getComboBoxCategories();

        MetaTag::setTags(['title' => 'Редактирование категории']);
        return view('blog.admin.category.edit',[
            'categories' => Category::with('children')->where('parent_id','0')->get(),
            'delimiter' => '-',
            'item' => $item,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->categoryRepository->getId($id);
        if (empty($item)){
            return back()
                ->withErrors(['msg' => "Запись = [{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();
        $result = $item->update($data);
        if ($result){
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => "Успешно сохранено"]);
        } else {
            return back()
                ->withErrors(['msg' => 'Ошибка сохранения!'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function destroy($id)
    {
        return view('blog.admin.category.index');
    }

    /**
     * @throws \Exception
     */
    public function mydel()
    {
        $id = $this->categoryRepository->getRequestID();

        if(!$id){
            return back()->withErrors(['msg' => 'Error ID']);
        }

        $children  = $this->categoryRepository->checkChildren($id);
        if ($children){
            return back()
                ->withErrors(['msg'=>'Удаление невозможно, в категории есть вложенные категории']);
        }

        $parents = $this->categoryRepository->checkParentsInProducts($id);
        if ($parents){
            return back()
                ->withErrors(['msg'=>'Удаление невозможно, в категории есть товары']);
        }

        $delete = $this->categoryRepository->deleteCategory($id);
        if ($delete){
            return redirect()
                ->route('blog.admin.categories.index')
                ->with(['success' => "Запись id [$id] удалена"]);
        }   else {
            return back()->withErrors(['msg' => 'Ошибка удаления']);
        }

    }

}
