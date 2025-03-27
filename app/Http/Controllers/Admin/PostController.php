<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Jobs\BlogPostAfterDeleteJob;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Redirect;

/**
 * Управление статьями блога
 *
 * @package App\Http\Controllers\Admin
 */
class PostController extends BaseController
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    /**
     * PostController constructor
     */
    function __construct()
    {
        parent::__construct();

        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();
        $categories = \App\Models\BlogCategory::withCount('Posts')->get();
        $users = \App\Models\User::all();

        return Inertia::render('Posts/Index', [
            'Posts' => $paginator,
            'categories' => $categories,
            'users' => $users,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param BlogPostCreateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();

        // **Загрузка и обработка изображения (если есть)**
        if ($request->hasFile('post_image')) {
            $image = $request->file('post_image');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $filename2 = Str::random(20) . '.' . $image->getClientOriginalExtension();


            $imageInstance = Image::make($image); // Intervention Image
            $imageInstance->resize(800, null, function ($constraint) { // Изменение размера (опционально)
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $imagePreview = Image::make($image);
            $imagePreview->resize(80, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });


            $pathToPreview = Storage::disk('public')->put('test2/', $filename2, $imagePreview->stream());


            $path = Storage::disk('public')->put('blog_post_images/' . $filename, $imageInstance->stream());
            $data['post_image'] = $path; // Сохраняем путь к изображению в данных поста
            $data['thumbnail_path'] = $pathToPreview;
        }
        $item = (new BlogPost())->create($data);

        if ($item) {
            $job = new BlogPostAfterCreateJob($item);
            $this->dispatch($job);

            return Redirect::route('Admin.Posts.edit', $item->id)
                ->with('success', 'Успешно Сохранено');
        } else {
            return Redirect::back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\Response
     */
    public function create()
    {
        $item = new BlogPost();
        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $item,
            'categoryList' => $categoryList,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }
        $comments = $item->comments()->whereNull('parent_id')->latest()->get();

        $categoryList = $this->blogCategoryRepository->getForComboBox();

        return Inertia::render('Admin/Posts/Edit', [
            'post' => $item,
            'categoryList' => $categoryList,
            'comments' => $comments,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlogPostUpdateRequest $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);

        if (empty($item)) {
            return Redirect::back()
                ->withErrors(['msg' => "Запись id=[{$id}] не найдена"])
                ->withInput();
        }

        $data = $request->all();

        // **Обновление изображения (если загружено новое)**
        if ($request->hasFile('post_image')) {
            // Удаление старого изображения (если есть) - Опционально, зависит от логики
            if ($item->post_image) {
                Storage::disk('public')->delete($item->post_image);
            }

            $image = $request->file('post_image');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();

            $imageInstance = Image::make($image);
            $imageInstance->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            Storage::disk('public')->put('test/' . $filename, $imageInstance->stream());

            $imagePreviewInstance = Image::make($image);
            $imagePreviewInstance->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            Storage::disk('public')->put('test/preview/' . $filename, $imagePreviewInstance->stream());

            $data['post_image'] = $filename;
        }

        $result = $item->update($data);

        if ($result) {
            return Redirect::route('Admin.Posts.edit', $item->id)
                ->with('success', 'Успешно сохранено');
        } else {
            return Redirect::back()
                ->withErrors(['msg' => 'Ошибка сохранения'])
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $item = $this->blogPostRepository->getEdit($id); // Получаем запись перед удалением

        if (empty($item)) {
            return Redirect::back()->withErrors(['msg' => 'Запись не найдена для удаления.']);
        }

        /** Soft delete: */
        $result = BlogPost::destroy($id);

        if ($result) {
            // **Удаление изображения после удаления поста (если есть) - Опционально, зависит от логики**
            if ($item->post_image) {
                Storage::disk('public')->delete($item->post_image);
            }

            BlogPostAfterDeleteJob::dispatch($id);

            return Redirect::route('Admin.Posts.index')
                ->with('deleted_id', $id)
                ->with('success', "Запись с ID {$id} успешно удалена.");
        } else {
            return Redirect::back()->withErrors(['msg' => 'Ошибка удаления.']);
        }
    }

    /**
     * Restore the specified resource from trash.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $post = BlogPost::onlyTrashed()->find($id);
        $post->restore();

        if (empty($post)) {
            return Redirect::back()->withErrors(['msg' => 'Ошибка Восстановления.']);
        } else {
            return Redirect::route('Admin.Posts.edit', $post->id)
                ->with('success', "Запись id: [$id] Восстановлена.");
        }
    }
}
