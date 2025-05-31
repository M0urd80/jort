<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'language' => 'required|in:fr,ar',
            'category' => 'nullable|string',
            'file' => 'required|file|mimes:pdf',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'published_at' => 'required|date',
        ]);

        $file = $request->file('file')->store('documents');

        $doc = Document::create([
            'title' => $request->title,
            'language' => $request->language,
            'category' => $request->category,
            'summary' => $request->summary,
            'content' => $request->content,
            'published_at' => $request->published_at,
            'file_path' => $file,
        ]);

        return response()->json(['message' => 'Document uploaded', 'id' => $doc->id]);
    }

    public function index()
    {
        return Document::latest()->paginate(20);
    }
}

