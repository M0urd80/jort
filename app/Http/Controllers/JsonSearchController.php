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

            // Log the incoming request params
            Log::info('🔍 Search request', [
                'q' => $query,
                'category' => $category,
                'year' => $year,
                'city' => $city,
                'capital_min' => $capitalMin
            ]);
            Log::info('Search query', $request->all());

            // Path to the JSON file
            $path = storage_path('app/public/notices/flat_index_notices.json');

            if (!file_exists($path)) {
                Log::error("❌ JSON file not found at: $path");
                return response()->json(['error' => 'Index file not found'], 404);
            }

            $json = file_get_contents($path);
            $notices = json_decode($json, true);

            // Check for JSON decoding errors
            if (json_last_error() !== JSON_ERROR_NONE) {
                $errorMsg = json_last_error_msg();
                Log::error("❌ JSON decode failed: $errorMsg");
                return response()->json([
                    'error' => 'Failed to decode JSON',
                    'json_error' => $errorMsg
                ], 500);
            }

            if (!is_array($notices)) {
                Log::error("❌ JSON structure invalid or empty.");
                return response()->json(['error' => 'Invalid JSON structure'], 500);
            }

            Log::info("✅ JSON loaded. Total notices: " . count($notices));

            $filtered = collect($notices)->filter(function ($item) use ($query, $category, $year, $city, $capitalMin) {
                return (!$query || str_contains($item['text'], $query))
                    && (!$category || $item['type'] === $category)
                    && (!$year || $item['year'] == $year)
                    && (!$city || str_contains($item['address'] ?? '', $city))
                    && (!$capitalMin || ($item['capital'] ?? 0) >= (int) $capitalMin);
            })->values();

            Log::info("✅ Filtered results count: " . $filtered->count());

            return response()->json($filtered);

        } catch (\Exception $e) {
            Log::error('❌ Search failed: ' . $e->getMessage());
            return response()->json(['error' => 'Search failed. Check logs.'], 500);
        }
    }
}

