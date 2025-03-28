<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogCategoryCreateRequest;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
use Inertia\Inertia;

class CategoryController extends BaseController
{
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    public function index()
    {
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(25);
        return Inertia::render('Admin/Categories/Index', ['paginator' => $paginator]);
    }

    public function create()
    {
        $categoryList = $this->blogCategoryRepository->getForComboBox();
        return Inertia::render('Admin/Categories/Edit', ['item' => new BlogCategory(), 'categoryList' => $categoryList]);
    }

    public function store(BlogCategoryCreateRequest $request)
    {
        $data = $request->input();
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }
        $item = (new BlogCategory())->create($data);
        if ($item) {
            return redirect()->route('admin.categories.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    public function edit($id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox();
        return Inertia::render('Admin/Categories/Edit', ['item' => $item, 'categoryList' => $categoryList]);
    }

    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {
            return back()->withErrors(['msg' => "Запись id=[$id] не найдена"])->withInput();
        }
        $data = $request->all();
        $result = $item->update($data);
        if ($result) {
            return redirect()->route('admin.categories.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }
}
