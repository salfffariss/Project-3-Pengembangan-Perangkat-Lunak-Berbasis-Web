<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

class ActivityService
{
    /**
     * Matriks aturan alur transisi status yang diizinkan (BR-03A).
     * Status hanya boleh tetap atau bergerak maju, tidak boleh mundur.
     */
    private const TRANSITIONS = [
        'Planned' => ['Planned', 'Ongoing'],
        'Ongoing' => ['Ongoing', 'Done'],
        'Done'    => ['Done'],
    ];

    /**
     * Membuat data kegiatan baru
     */
    public function create(array $data): Activity
    {
        return Activity::create($data);
    }

    /**
     * Memperbarui data kegiatan dengan memvalidasi transisi status
     */
    public function update(Activity $activity, array $data): Activity
    {
        $nextStatus = $data['status'] ?? $activity->status;

        // Periksa apakah perubahan status diizinkan
        $this->ensureValidTransition($activity->status, $nextStatus);

        // Jika lolos aturan bisnis, simpan perubahan ke database
        $activity->update($data);

        return $activity->refresh();
    }

    /**
     * Aturan Bisnis: Memastikan transisi status berjalan maju
     */
    private function ensureValidTransition(string $current, string $next): void
    {
        $allowed = self::TRANSITIONS[$current] ?? [];

        if (! in_array($next, $allowed, true)) {
            throw new DomainException("Transisi status ({$current} ke {$next}) tidak diizinkan.");
        }
    }
}