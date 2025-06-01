<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

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


public function flatJsonSearch(Request $request)
{
    $keyword = $request->input('q');
    $category = $request->input('category');
    $year = $request->input('year');
    $city = $request->input('city');
    $capitalMin = $request->input('capital_min');

    // Load JSON from disk
    $json = Storage::disk('public')->get('notices/flat_index_notices.json');
    $notices = json_decode($json, true);

    // Filter logic
    $results = array_filter($notices, function ($n) use ($keyword, $category, $year, $city, $capitalMin) {
        $ok = true;

        if ($keyword) {
            $ok = $ok && (stripos($n['text'] ?? '', $keyword) !== false);
        }
        if ($category) {
            $ok = $ok && ($n['type'] === $category);
        }
        if ($year) {
            $ok = $ok && ($n['issue_year'] === $year);
        }
        if ($city) {
            $ok = $ok && (isset($n['address']) && stripos($n['address'], $city) !== false);
        }
        if ($capitalMin) {
            $ok = $ok && (isset($n['capital']) && floatval($n['capital']) >= floatval($capitalMin));
        }

        return $ok;
    });

    return response()->json(array_values($results));
}

}

