<?php

namespace App\Services;

use App\Models\Activity;
use App\Models\Registration;
use DomainException;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    /**
     * Mendaftarkan peserta ke kegiatan secara atomic menggunakan DB::transaction.
     *
     * @param Activity $activity
     * @param array{participant_name: string, email: string} $data
     * @param callable|null $beforeCommit Hook opsional untuk simulasi kegagalan rollback
     * @return Registration
     * @throws DomainException
     */
    public function register(Activity $activity, array $data, ?callable $beforeCommit = null): Registration
    {
        // IC-01: Hanya untuk Activity berstatus published
        if ($activity->status !== 'published') {
            throw new DomainException('Pendaftaran hanya dapat dilakukan untuk kegiatan berstatus Published.');
        }

        // IC-02: Ditolak jika tanggal mulai sudah lewat
        if ($activity->start_at->isPast() && !$activity->start_at->isToday()) {
            throw new DomainException('Pendaftaran ditolak karena kegiatan sudah dimulai atau telah lewat.');
        }

        // IC-03: Email yang sama tidak boleh mendaftar dua kali pada kegiatan yang sama
        if ($activity->registrations()->where('email', $data['email'])->exists()) {
            throw new DomainException('Email ini sudah terdaftar pada kegiatan ini.');
        }

        // IC-04: Jumlah pendaftar tidak boleh melebihi kapasitas
        if ($activity->registered_count >= $activity->capacity) {
            throw new DomainException('Kapasitas peserta untuk kegiatan ini sudah penuh.');
        }

        // IC-05: Eksekusi pendaftaran dan pembaruan counter dalam satu Database Transaction
        return DB::transaction(function () use ($activity, $data, $beforeCommit) {
            $registration = Registration::create([
                'activity_id' => $activity->id,
                'participant_name' => $data['participant_name'],
                'email' => $data['email'],
                'registered_at' => now(),
            ]);

            $activity->increment('registered_count');

            // Hook untuk pengujian rollback terkontrol (IC-06)
            if ($beforeCommit !== null) {
                $beforeCommit($registration, $activity);
            }

            return $registration;
        });
    }
}
