<?php

namespace Modules\Buku\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BukuRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'penerbit' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'digits:4', 'min:1900', 'max:'.date('Y')],
            'stok' => ['required', 'integer', 'min:0', 'max:100000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'tahun_terbit.digits' => 'Tahun terbit harus terdiri dari 4 angka.',
            'tahun_terbit.max' => 'Tahun terbit tidak boleh melebihi tahun sekarang.',
            'stok.min' => 'Stok tidak boleh bernilai negatif.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }
}
