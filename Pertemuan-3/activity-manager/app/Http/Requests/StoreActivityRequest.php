<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreActivityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Izinkan semua request diproses
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'max:30', 'unique:activities,code'],
            'title' => ['required', 'string', 'min:5', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'activity_date' => ['required', 'date'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'status' => ['required', Rule::in(['Planned', 'Ongoing', 'Done'])],
        ];
    }

    public function messages(): array
    {
        return [
            'code.unique' => 'Kode kegiatan sudah digunakan.',
            'category_id.exists' => 'Kategori yang dipilih tidak valid atau tidak ditemukan.',
        ];
    }
}
