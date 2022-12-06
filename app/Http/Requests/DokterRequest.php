<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DokterRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nama_dokter' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' =>  ['required', 'string', 'max:255'],
            'telp' => ['required','string','max:225'],
            'NIK'=>['required','number','max:225'],
            'no_STR'=>['required','number','max:225'],
            'no_SIP'=>['required','number','max:225'],
            'tanggal_lahir'=>['required','date','max:225'],
            'rumah_sakit'=>['required','string','max:225']
        ];
    }
}
