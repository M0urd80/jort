<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class SearchController extends Controller
{
    public function searchPreview(Request $request)
    {
        $query = $request->input('query');
        $language = $request->input('lang', 'fr');
        $category = $request->input('category');

        $results = Document::select('id', 'title', 'date', 'summary', 'language')
            ->when($query, fn($q) => $q->where('content', 'ilike', "%$query%"))
            ->when($language, fn($q) => $q->where('language', $language))
            ->when($category, fn($q) => $q->where('category', $category))
            ->take(10)
            ->get();

        return response()->json(['results' => $results]);
    }

    public function viewDocument($id)
    {
        $document = Document::findOrFail($id);
        return response()->json($document);
    }


    public function index()
{
    $documents = Document::select('id', 'title', 'date', 'language', 'category')
        ->orderByDesc('date')
        ->take(50)
        ->get();

    return response()->json($documents);
}


}

