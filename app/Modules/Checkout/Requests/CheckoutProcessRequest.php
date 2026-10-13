<?php

namespace App\Modules\Checkout\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutProcessRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'address_id' => 'nullable|integer|exists:user_addresses,id',
            'recipient_name' => 'required_without:address_id|string|max:255',
            'phone_number' => 'required_without:address_id|string|max:20',
            'address_line' => 'required_without:address_id|string',
            'province_name' => 'required_without:address_id|string|max:100',
            'city_name' => 'required_without:address_id|string|max:100',
            'district_name' => 'nullable|string|max:100',
            'postal_code' => 'required_without:address_id|string|max:10',
            'save_address' => 'nullable|boolean',

            'courier_code' => 'required|string|in:jne,sicepat,jnt,flat',
            'payment_gateway' => 'required|string|in:qrisly,manual_transfer',
            'customer_note' => 'nullable|string|max:500',
        ];
    }
}
