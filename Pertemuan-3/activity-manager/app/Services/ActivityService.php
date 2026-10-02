<?php

namespace App\Services;

use App\Models\Activity;
use DomainException;

class ActivityService
{
    /**
     * Membuat data kegiatan baru dengan status default 'draft'
     */
    public function create(array $data): Activity
    {
        $data['status'] = 'draft';

        return Activity::create($data);
    }

    /**
     * Memperbarui data kegiatan (tanpa mengubah status secara bebas)
     */
    public function update(Activity $activity, array $data): Activity
    {
        // Status tidak dapat diubah melalui form edit umum (Task 2 - Item 4)
        unset($data['status']);

        $activity->update($data);

        return $activity->refresh();
    }

    /**
     * Mempublikasikan kegiatan (Draft -> Published)
     * Memenuhi aturan BR-05 dan BR-06.
     */
    public function publish(Activity $activity): Activity
    {
        if ($activity->status !== 'draft') {
            throw new DomainException("Hanya kegiatan berstatus draft yang dapat dipublikasikan (Status saat ini: {$activity->status}).");
        }

        // BR-05: Pemeriksaan kelengkapan field sebelum publish
        $this->ensurePublishPrerequisites($activity);

        $activity->update(['status' => 'published']);

        return $activity->refresh();
    }

    /**
     * Menyelesaikan kegiatan (Published -> Completed)
     * Memenuhi aturan BR-06.
     */
    public function complete(Activity $activity): Activity
    {
        if ($activity->status !== 'published') {
            throw new DomainException("Hanya kegiatan berstatus published yang dapat diselesaikan (Status saat ini: {$activity->status}).");
        }

        $activity->update(['status' => 'completed']);

        return $activity->refresh();
    }

    /**
     * Validasi transisi status umum (BR-06 & BR-07)
     */
    public function transitionTo(Activity $activity, string $nextStatus): Activity
    {
        if ($activity->status === 'completed') {
            throw new DomainException('Kegiatan yang sudah completed tidak dapat diubah statusnya kembali.');
        }

        return match ($nextStatus) {
            'published' => $this->publish($activity),
            'completed' => $this->complete($activity),
            default => throw new DomainException("Transisi status ({$activity->status} ke {$nextStatus}) tidak diizinkan."),
        };
    }

    /**
     * BR-05: Memastikan seluruh data esensial telah lengkap sebelum dipublikasikan
     */
    private function ensurePublishPrerequisites(Activity $activity): void
    {
        $missing = [];

        if (empty($activity->code)) {
            $missing[] = 'kode kegiatan';
        }
        if (empty($activity->title)) {
            $missing[] = 'judul';
        }
        if (empty($activity->category_id)) {
            $missing[] = 'kategori';
        }
        if (empty($activity->location)) {
            $missing[] = 'lokasi';
        }
        if (empty($activity->start_at)) {
            $missing[] = 'tanggal mulai';
        }
        if (empty($activity->end_at)) {
            $missing[] = 'tanggal selesai';
        }
        if (empty($activity->capacity) || $activity->capacity < 1) {
            $missing[] = 'kapasitas';
        }

        if (! empty($missing)) {
            $fields = implode(', ', $missing);
            throw new DomainException("Kegiatan tidak dapat dipublikasikan karena data belum lengkap: {$fields}.");
        }
    }
}
