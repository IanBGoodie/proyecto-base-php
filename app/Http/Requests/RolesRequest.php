<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        if ($this->method() == 'PUT') {
            $params = $this->route()->parameters();
            $id = $params["role"];

            return [
                "name" => "required|string|unique:roles,id,$id",
                "slug" => "required|string|unique:roles,slug,{$id}",
                "permissions" => "nullable|string",
            ];



        } else {
            return [
            "name" => "required|string|unique:roles",
            "slug" => "required|string|unique:roles",
            "permissions" => "nullable|string",
            ];
        }


    }
}
