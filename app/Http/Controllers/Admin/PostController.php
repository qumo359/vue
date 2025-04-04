<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BlogPostCreateRequest;
use App\Http\Requests\BlogPostUpdateRequest;
use App\Jobs\BlogPostAfterCreateJob;
use App\Models\BlogPost;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogPostRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends BaseController
{
    private $blogPostRepository;
    private $blogCategoryRepository;

    function __construct()
    {
        parent::__construct();
        $this->blogPostRepository = app(BlogPostRepository::class);
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }

    public function index()
    {
        $paginator = $this->blogPostRepository->getAllWithPaginate();
        $categories = \App\Models\BlogCategory::withCount('posts')->get();
        $users = \App\Models\User::all();
        return Inertia::render('Admin/Posts/Index', compact('paginator', 'categories', 'users'));
    }

    public function store(BlogPostCreateRequest $request)
    {
        $data = $request->input();
        if ($request->hasFile('post_image')) {
            $image = $request->file('post_image');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $filename2 = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $imageInstance = Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $imagePreview = Image::make($image)->resize(80, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            $pathToPreview = Storage::disk('public')->put('test2/', $filename2, $imagePreview->stream());
            $path = Storage::disk('public')->put('blog_post_images/' . $filename, $imageInstance->stream());
            $data['post_image'] = $path;
            $data['thumbnail_path'] = $pathToPreview;
        }


        $item = (new BlogPost())->create($data);
        if ($item) {
            BlogPostAfterCreateJob::dispatch($item);
            return redirect()->route('admin.posts.edit', [$item->id])
                ->with(['success' => 'Успешно Сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ощибка сохранения'])->withInput();
        }
    }

    public function create()
    {
        $categoryList = $this->blogCategoryRepository->getForComboBox();
        return Inertia::render('Admin/Posts/Edit', ['item' => new BlogPost(), 'categoryList' => $categoryList, 'comments' => []]);
    }

    public function edit($id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            abort(404);
        }
        $comments = $item->comments()->whereNull('parent_id')->latest()->get();
        $categoryList = $this->blogCategoryRepository->getForComboBox();
        return Inertia::render('Admin/Posts/Edit', compact('item', 'categoryList', 'comments'));
    }

    public function update(BlogPostUpdateRequest $request, $id)
    {
        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()->withErrors(['msg' => 'Запись id=[{$id}] не найдена'])->withInput();
        }
        $data = $request->all();
        if ($request->hasFile('post_image')) {
            if ($item->post_image) {
                Storage::disk('public')->delete($item->post_image);
            }
            $image = $request->file('post_image');
            $filename = Str::random(20) . '.' . $image->getClientOriginalExtension();
            $imageInstance = Image::make($image)->resize(1600, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            Storage::disk('public')->put('test/' . $filename, $imageInstance->stream());
            $imagePreviewInstance = Image::make($image)->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
            Storage::disk('public')->put('test/preview/' . $filename, $imagePreviewInstance->stream());
            $data['post_image'] = $filename;
        }
        $result = $item->update($data);
        if ($result) {
            return redirect()->route('admin.posts.edit', $item->id)
                ->with(['success' => 'Успешно сохранено']);
        } else {
            return back()->withErrors(['msg' => 'Ошибка сохранения'])->withInput();
        }
    }

    public function destroy($id)
    {

        $item = $this->blogPostRepository->getEdit($id);
        if (empty($item)) {
            return back()->withErrors(['msg' => 'Запись не найдена для удаления.']);
        }
        $result = BlogPost::destroy($id);
        if ($result) {
            if ($item->post_image) {
                Storage::disk('public')->delete($item->post_image);
            }
            \App\Jobs\BlogPostAfterDeleteJob::dispatch($id);
            return redirect()->route('admin.posts.index')
                ->with(['deleted_id' => $id, 'success' => "Запись с ID {$id} успешно удалена."]);
        } else {
            return back()->withErrors(['msg' => 'Ошибка удаления.']);
        }
    }

    public function restore($id)
    {
        $post = BlogPost::onlyTrashed()->find($id);
        $post->restore();
        if (empty($post)) {
            return back()->withErrors(['msg' => 'Ошибка Восстановления.']);
        } else {
            return redirect()->route('admin.posts.edit', $post->id)
                ->with(['success' => "Запись id: [$id] Восстановлена."]);
        }
    }
}
