<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAssetRequest extends FormRequest
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
            'asset_name' => 'required',Rule::unique('assets')->where('deleted_at',null),
            'asset_code' => 'nullable|max:30|'.Rule::unique('assets')->where('deleted_at',null),
            'asset_class' => 'nullable|max:30',
            'asset_facility' => 'nullable|max:10',
            'asset_type' => 'nullable|max:50',
            'serial_number' => 'nullable|max:50',
            'old_tag' => 'nullable|max:50',
            'description' => 'nullable|max:250',
            'sap_number' => 'nullable|max:100',
            'do_number' => 'nullable|max:100',
            'do_date' => 'nullable',
            'po_number' => 'nullable',
            'site_id' => 'required',
            'floor_id' => 'nullable',
            'room_id' => 'nullable',
        ];
    }
}
