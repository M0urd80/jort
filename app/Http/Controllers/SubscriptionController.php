<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->subscriptions);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:basic,advanced',
            'duration' => 'required|in:1_month,6_months,12_months',
        ]);
            // ✅ Prevent duplicate pending subscriptions
    $existing = Subscription::where('user_id', $request->user()->id)
        ->where('status', 'pending')
        ->first();

    if ($existing) {
        return response()->json([
            'error' => 'You already have a pending subscription.',
            'subscription' => $existing,
        ], 409); // 409 = Conflict
    }

        $subscription = Subscription::create([
            'user_id' => $request->user()->id,
            'type' => $request->type,
            'duration' => $request->duration,
            'status' => 'pending',
        ]);

        return response()->json(['message' => 'Subscription added to cart', 'subscription' => $subscription]);
    }

    public function activate(Request $request)
    {
        $subscription = Subscription::where('user_id', $request->user()->id)
            ->where('status', 'pending')
            ->latest()
            ->first();

        if (!$subscription) {
            return response()->json(['error' => 'No pending subscription found'], 404);
        }

        $now = Carbon::now();
        $end = match ($subscription->duration) {
            '1_month' => $now->copy()->addMonth(),
            '6_months' => $now->copy()->addMonths(6),
            '12_months' => $now->copy()->addYear(),
        };

        $subscription->update([
            'status' => 'active',
            'start_date' => $now,
            'end_date' => $end,
        ]);

        return response()->json(['message' => 'Subscription activated', 'subscription' => $subscription]);
    }
}

