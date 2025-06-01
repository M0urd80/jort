<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class JsonSearchController extends Controller
{
  public function search(Request $request)
{
    try {
        $query = $request->input('q');
        $category = $request->input('category');
        $year = $request->input('year');
        $city = $request->input('city');
        $capitalMin = $request->input('capital_min');
        $page = max((int)$request->input('page', 1), 1);
        $limit = max((int)$request->input('limit', 10), 1);

        $path = storage_path('app/public/notices/flat_index_notices.json');

        if (!file_exists($path)) {
            return response()->json(['error' => 'Index file not found'], 404);
        }

        $json = file_get_contents($path);
        $notices = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json(['error' => 'Failed to decode JSON', 'json_error' => json_last_error_msg()], 500);
        }

        if (!is_array($notices)) {
            return response()->json(['error' => 'Invalid JSON structure'], 500);
        }

        // Filtering
        $filtered = collect($notices)->filter(function ($item) use ($query, $category, $year, $city, $capitalMin) {
            return (!$query || str_contains($item['text'], $query))
                && (!$category || $item['type'] === $category)
                && (!$year || $item['year'] == $year)
                && (!$city || str_contains($item['address'] ?? '', $city))
                && (!$capitalMin || ($item['capital'] ?? 0) >= (int)$capitalMin);
        });

        // Sorting by score (desc)
        $sorted = $filtered->sortByDesc('score')->values();

        // Pagination
        $total = $sorted->count();
        $results = $sorted->forPage($page, $limit)->values();

        return response()->json([
            'total' => $total,
            'page' => $page,
            'per_page' => $limit,
            'total_pages' => ceil($total / $limit),
            'results' => $results
        ]);

    } catch (\Exception $e) {
        \Log::error('Search failed: ' . $e->getMessage());
        return response()->json(['error' => 'Search failed. Check logs.'], 500);
    }
}


}

