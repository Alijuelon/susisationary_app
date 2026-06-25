<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Queue;
use App\Enums\QueueStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class QueueController extends Controller
{
    /**
     * Get the current active queue for hardware output (LCD).
     * Lightweight JSON structure.
     */
    public function activeQueue(): JsonResponse
    {
        // Find the latest queue that is being processed
        $activeQueue = Queue::where('status', QueueStatus::DIPROSES)
            ->latest('processed_at')
            ->first();

        if (!$activeQueue) {
            return response()->json([
                'q' => '-',
                's' => '-',
                'c' => 0
            ]);
        }

        return response()->json([
            'q' => $activeQueue->queue_number,
            's' => strtoupper($activeQueue->status->value),
            'c' => 1 // Counter hardcoded to 1 or dynamic based on who processed it
        ]);
    }
}
