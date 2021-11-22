<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhotoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "photo" => ['image','mimes:jpg,png,jpeg,gif','max:2048','nullable']
        ];
    }

    public function messages()
    {
        return [
            'image.image' => 'File yang diupload bukan gambar',
            'image.max' => 'Gagal mengupload gambar, maximum ukuran gambar 2MB.',
        ];
    }
}
