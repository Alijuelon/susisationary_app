<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Queue;
use App\Models\Pengaturan;
use App\Enums\QueueStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class QueueController extends Controller
{
    /**
     * Customer - Take Queue
     */
    public function takeQueue(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check if queue is active
            $setting = Pengaturan::first();
            if ($setting && !$setting->antrian_aktif) {
                return back()->with('error', 'Antrian sedang ditutup.');
            }

            // Get last queue number today
            $lastQueue = Queue::whereDate('created_at', Carbon::today())
                ->orderBy('id', 'desc')
                ->lockForUpdate() // Prevent race conditions
                ->first();

            $nextNumber = 1;
            if ($lastQueue) {
                // Assuming format A-001
                $parts = explode('-', $lastQueue->queue_number);
                if (count($parts) == 2) {
                    $nextNumber = intval($parts[1]) + 1;
                }
            }

            $queueNumber = 'A-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $queue = Queue::create([
                'queue_number' => $queueNumber,
                'customer_id' => auth()->id(), // Associate with logged in user
                'status' => QueueStatus::MENUNGGU,
            ]);
            
            DB::commit();

            return back()->with('success', 'Antrian berhasil diambil. Estimasi waktu: ' . $queue->estimated_wait_time . ' menit.');
            
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mengambil antrian: ' . $e->getMessage());
        }
    }

    /**
     * Customer - Cancel Queue
     */
    public function cancelQueue($id)
    {
        $queue = Queue::findOrFail($id);

        // Validation: Only "Menunggu" can be cancelled
        if ($queue->status !== QueueStatus::MENUNGGU) {
            return back()->with('error', 'Hanya antrian berstatus Menunggu yang dapat dibatalkan.');
        }

        $queue->update([
            'status' => QueueStatus::DIBATALKAN,
            'cancelled_at' => now(),
        ]);

        return back()->with('success', 'Antrian berhasil dibatalkan.');
    }

    /**
     * Cashier - Process Queue
     */
    public function processQueue($id)
    {
        $queue = Queue::findOrFail($id);

        // Validation: Cashier can only process "Menunggu" queue
        if ($queue->status !== QueueStatus::MENUNGGU) {
            return back()->with('error', 'Hanya antrian berstatus Menunggu yang dapat diproses.');
        }

        $queue->update([
            'status' => QueueStatus::DIPROSES,
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Antrian ' . $queue->queue_number . ' sedang diproses. LCD telah diperbarui.');
    }

    /**
     * Cashier - Complete Queue
     */
    public function completeQueue($id)
    {
        $queue = Queue::findOrFail($id);

        // Validation: "Selesai" cannot be changed to "Diproses" again, only "Diproses" can be completed
        if ($queue->status !== QueueStatus::DIPROSES) {
            return back()->with('error', 'Hanya antrian berstatus Diproses yang dapat diselesaikan.');
        }

        $queue->update([
            'status' => QueueStatus::SELESAI,
            'completed_at' => now(),
        ]);

        return back()->with('success', 'Antrian ' . $queue->queue_number . ' telah diselesaikan.');
    }
}
