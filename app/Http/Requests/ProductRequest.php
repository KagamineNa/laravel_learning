<?php

namespace App\Http\Requests;

use App\Rules\Upercase;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ["required", "min:5", new Upercase()],
            "price" => ["required", new Upercase()],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Vui long nhap :attribute',
            'min' => ':attribute phai co it nhat :min ky tu',
            'numeric' => ':attribute phai la so',
        ];
    }

    public function attributes(): array
    {
        return [
            "name" => "Ten san pham",
            "price" => "Gia san pham",
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->count() > 0) {
                $validator->errors()->add("msg", "Co loi xay ra, vui long kiem tra lai");
            }
        });
    }

    public function prepareForValidation()
    {
        $this->merge([
            "created_at" => date("Y-m-d H:i:s"),
        ]);
    }

    protected function failedAuthorization()
    {
        // throw new AuthorizationException("Ban khong co quyen thuc hien hanh dong nay");
        // throw new HttpResponseException(redirect('/masterlayout')->with('msg', 'Ban khong co quyen thuc hien hanh dong nay')->with('type', 'danger'));
        throw new HttpResponseException(abort(404));
    }
}
