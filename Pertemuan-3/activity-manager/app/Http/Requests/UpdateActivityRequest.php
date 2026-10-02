<?php

namespace App\Http\Requests;

use App\Models\Activity;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $activity = $this->route('activity');
        $activityId = $activity instanceof Activity ? $activity->id : $activity;

        return [
            'code' => ['required', 'string', 'max:30', Rule::unique('activities', 'code')->ignore($activityId)],
            'title' => ['required', 'string', 'min:5', 'max:150'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'start_at' => ['required', 'date'],
            'end_at' => ['required', 'date', 'after_or_equal:start_at'],
            'capacity' => ['required', 'integer', 'min:1', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Kode kegiatan sudah digunakan.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid atau tidak ditemukan.',
            'end_at.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal daripada tanggal mulai.',
            'capacity.min' => 'Kapasitas harus berupa bilangan positif minimal 1.',
            'capacity.max' => 'Batas maksimum kelas adalah 500 peserta.',
        ];
    }
}
