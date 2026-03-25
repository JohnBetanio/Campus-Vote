<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::orderBy('created_at', 'desc')->get()->map(fn ($a) => [
            'id' => $a->id,
            'content' => $a->content,
            'created_at' => $a->created_at?->toISOString(),
            'updated_at' => $a->updated_at?->toISOString(),
            'update_url' => route('admin.announcements.update', $a, false),
            'destroy_url' => route('admin.announcements.destroy', $a, false),
        ])->values()->all();
        return Inertia::render('Admin/Announcements', ['announcements' => $announcements]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        Announcement::create([
            'content' => $request->input('content')
        ]);
        
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement created successfully!');
    }
      // NEW: Show edit form
    public function edit(Announcement $announcement)
    {
        return response()->json($announcement);
    }
     // NEW: Update announcement
    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $announcement->update($validated);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('admin.announcements.index')->with('success', 'Announcement deleted successfully!');
    }
}