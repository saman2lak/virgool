<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AllUserDraftController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response([
            'data' => $request->user()->drafts->map(function ($draft) {
                return collect($draft)->merge(
                    [
                        'updated_at' => verta($draft->updated_at)->formatDifference()
                    ]
                );
            }),
            'draft_count' => $request->user()->drafts->count(),
            'post_count' => $request->user()->posts->count()
        ], 200);
    }
}