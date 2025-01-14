<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Requests\TagStoreRequest;
use App\Http\Requests\TagUpdateRequest;
use Exception;
use Illuminate\Support\Facades\Storage;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::paginate();
        return Inertia::render('Tags/Index', [
            'tags' => $tags,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Tags/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TagStoreRequest $request)
    {
        $data = $request->validated();
        Tag::create($data);
        // return redirect()->route('tags.index')->with('success', 'Tag created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TagUpdateRequest $request, Tag $tag)
    {
        $data = $request->validated();
        $res = $tag->update($data);
        return to_route('tags.index')->with('success', 'Tag updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        if ($tag->photo && Storage::fileExists($tag->photo)) {
            Storage::delete($tag->photo);
        }
        $tag->delete();
        return to_route('tags.index')->with('success', 'Tag deleted successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate(['tagIds' => 'required|array']);
        $tagIds = $request->input('tagIds');
        foreach ($tagIds as $id) {
            $tag = Tag::find($id);
            if ($tag->photo && Storage::fileExists($tag->photo)) {
                Storage::delete($tag->photo);
            }
            $tag->delete();
        }
        // Tag::destroy($tagIds);
        return to_route('tags.index')->with('success', 'Tags deleted successfully');
    }
}
