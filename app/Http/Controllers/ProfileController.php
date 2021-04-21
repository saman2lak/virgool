<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    //
    public function update(ProfileRequest $request){
        //


        $data = $request->only([
        'name',
        'email',
        'password',
        'bio',
        'username',
        'email_on_follow',
        'email_on_like',
        'email_on_replay',
        ]);

        if(empty($data['password'])){
            unset($data['password']);
        }else{
            $data['password'] = Hash::make($request->password);
        }

        if($request->profile && $request->profile_name){
            $file_name =$request->user()->username .'.'. Str::afterLast($request->profile_name, '.');
            $path = public_path('profiles/').$file_name;
            Image::make($request->profile)->fit(100)->save($path);
            $data['profile']=$file_name . '?' .Str::random(16);
        }

        $request->user()->update($data);
        return $request->user();
    }
}