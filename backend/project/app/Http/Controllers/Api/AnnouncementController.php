<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:30',
            'content' => 'required|string|max:1000',
            'category' => 'required|in:notice,maintenance,update',
            'is_published' => 'boolean',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after:start_at',
            'show_banner' => 'boolean',
            'banner_start_at' => 'nullable|required_if:show_banner,true|date',
            'banner_end_at' => 'nullable|required_if:show_banner,true|date|after:banner_start_at',
            'send_notification' => 'boolean'
        ]);

        $announcement = Announcement::create($validatedData);

        return response()->json([
            'message' => 'お知らせを作成しました',
            'data' => $announcement
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
