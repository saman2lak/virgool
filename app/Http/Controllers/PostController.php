<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    public function store(PostRequest $request)
    {
        $data = $request->only(['title', 'content', 'desc']);
        $data['desc'] = Str::words($data['desc'], 50, '...') ?? Str::words(strip_tags($data['content']), 50, '...');
        $data['slug'] =  $this->createSlug($request->title);
        $data['user_id'] = $request->user()->id;
        $min_read = ceil(str_word_count(strip_tags($data['content'])) / 250);
        $data['min_read'] = $min_read;
        $data['short_link'] = Str::random(7);

        if ($request->image) {
            $file_name = Str::random(16) . '-' . $request->image_name;
            $path = Post::getPublicDirectory() . $file_name;
            Image::make($request->image)->save($path);
            $data['image'] = $file_name;
        }

        $post = Post::create($data);

        $selectedCategories = Category::whereIn('title', $request->categories)->get();
        $shouldCreateCategories = collect($request->categories)->diff($selectedCategories->pluck('title'));

        $CreatedCategories = [];
        foreach ($shouldCreateCategories->toArray() as $catTitle) {
            $CreatedCategories[] = Category::create([
                'title' => $catTitle,
                'slug' => str_replace(' ', '-', $catTitle),
            ]);
            $post->categories()->sync(
                collect($CreatedCategories)->pluck('id')->concat($selectedCategories->pluck('id'))
            );
        }

        return response(['data' => $post], 200);
    }

    public function createSlug($title, $id = 0)
    {
        $slug = str_replace(' ', '-', $title);
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        // اگر اسلاگ تکراری بود و از قبل وجود داشت
        $i = 1;
        $is_contain = true;
        do {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                $is_contain = false;
                return $newSlug;
            }
            $i++;
        } while ($is_contain);
    }
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return Post::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }



    public function show(Post $post)
    {
        $this->authorize('view', $post);
        return $post->append('categories_title');
    }

    public function edit(UpdatePostRequest $request, Post $post)
    {
        $post->update($request->validated());
        return $post;
    }

    public function update(PostRequest $request, Post $post)
    {
        $data = $request->only(['title', 'content', 'desc']);
        $data['desc'] = Str::words($data['desc'], 50, '...') ?? Str::words(strip_tags($data['content']), 50, '...');
        $data['slug'] =  $this->createSlug($request->title);
        $data['user_id'] = $request->user()->id;
        $min_read = ceil(str_word_count(strip_tags($data['content'])) / 250);
        $data['min_read'] = $min_read;

        if ($request->image_name) {
            $file_name = Str::random(16) . '-' . $request->image_name;
            $path = Post::getPublicDirectory() . $file_name;
            Image::make($request->image)->save($path);
            $data['image'] = $file_name;
        } else {
            unset($data['image']);
        }

        $post->update($data);

        $selectedCategories = Category::whereIn('title', $request->categories)->get();
        $shouldCreateCategories = collect($request->categories)->diff($selectedCategories->pluck('title'));

        $CreatedCategories = [];
        foreach ($shouldCreateCategories->toArray() as $catTitle) {
            $CreatedCategories[] = Category::create([
                'title' => $catTitle,
                'slug' => str_replace(' ', '-', $catTitle),
            ]);
            $post->categories()->attach(
                collect($CreatedCategories)->pluck('id')->concat($selectedCategories->pluck('id'))
            );
        }

        return response(['data' => $post], 200);
    }

    public function destroy(Post $post)
    {
        $this->authorize('view', $post);
        $post->delete();
        return response([
            'data' => 'deleted'
        ], 200);
    }

    public function showPost(Post $post)
    {
        $relatedPosts = Post::with('user')
            ->where('id', '!=', $post->id)
            ->whereHas('categories', function ($query) use ($post) {
                $query->whereIn('categories.id', $post->categories->pluck('id'));
            })->inRandomOrder()->take(3)->get();


        $post->load(['user', 'categories', 'parentComments'])
            ->loadCount('comments', 'likes')
            ->append('is_liked');

        $post->user->append('is_follows');



        return response([
            'post' => $post,
            'relatedPosts' => $relatedPosts
        ], 200);
    }

    public function short_link(Post $post)
    {
        return redirect("/post/{$post->slug}");
    }

    public function userpost(User $user)
    {
        //
        return response([
            'post' => $user->posts()->with('user', 'categories')->withCount('likes')->simplePaginate(10),
            'user' => $user->loadCount('posts')->append('is_follows')
        ], 200);
    }

    public function likedPost(Request $request)
    {
        $posts = $request->user()->likes()
            ->with('user', 'categories')
            ->withCount('likes')
            ->simplePaginate(10);

        return response([
            'posts' => $posts,
            'user' => $request->user()->loadCount('likes')
        ], 200);
    }

    public function bookmarkedPost(Request $request)
    {
        $posts = $request->user()->bookmarks()
            ->with('user', 'categories')
            ->withCount('bookmarks')
            ->simplePaginate(10);

        return response([
            'posts' => $posts,
            'user' => $request->user()->loadCount('bookmarks')
        ], 200);
    }

    public function homePost()
    {
        return response([
            'posts' => Post::with('user', 'categories')->withCount('likes')
                ->orderbyDesc('created_at')->simplePaginate(10),
        ], 200);
    }
}