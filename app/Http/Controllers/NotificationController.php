<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index(Request $request)
    {
        //
        return response([
            'data' => $request->user()->notifications()
                ->take(20)->get(),
            'notifyCount' => $request->user()->notifications()->where('read_at')->count()
        ], 200);
    }

    public function update(Request $request, $id)
    {
        //
        $request->user()->notifications()
            ->where('id', $id)->first()->markAsRead();
        return response([
            'data' => 'read Notif'
        ], 200);
    }
}