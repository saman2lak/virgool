<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UploadPostImageController extends Controller
{
    //

    public function __invoke(Request $request){
        $request->validate([
            'file' => ['required','image']
        ]);

        $image = $request->file('file');
        $image_name = Post::getFileName($image);
        $image_dir = Post::getPublicDirectory();
        $image->move($image_dir,$image_name);

        return response([
            'data' => Post::getImageDirectory().$image_name
        ]);
    }
}