<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\QueueStatus;

class Queue extends Model
{
    use HasFactory;

    protected $fillable = [
        'queue_number',
        'customer_id',
        'status',
        'processed_at',
        'completed_at',
        'cancelled_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => QueueStatus::class,
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    /**
     * Calculate estimated wait time in minutes for this queue.
     */
    public function getEstimatedWaitTimeAttribute(): int
    {
        if ($this->status !== QueueStatus::MENUNGGU) {
            return 0; // No wait time if not waiting
        }

        // Count queues ahead of this one that are still waiting
        $queuesAhead = self::where('status', QueueStatus::MENUNGGU)
            ->where('created_at', '<', $this->created_at)
            ->count();

        $setting = \App\Models\Pengaturan::first();
        $avgProcessTime = $setting ? $setting->waktu_proses_antrian : 5;

        return $queuesAhead * $avgProcessTime;
    }
}
