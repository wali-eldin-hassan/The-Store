<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCustomerServiceRequest extends FormRequest
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
            'customer_phone' => 'sometimes',
            'customer_alt_phone' => 'sometimes',
            'note'=>'sometimes',
            'customer_address' => 'sometimes',
            'discount' => 'sometimes|nullable|regex:/^[0-9]\d*(\.\d+)?$/',
            'order_details.*.product_id' => 'sometimes',
            'order_details.*.quantity' => 'sometimes',
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'customer_phone.required' => 'رقم العميل مطلوب',
            'customer_address.required' => 'عنوان العميل مطلوب',
            'added_price.regex' => 'يجب ان يكون عدد عشري او صحيح',
            'discount.regex' => 'يجب ان يكون عدد عشري او صحيح',
            'order_details.*.product_id.required' => 'المنتج مطلوب',
            'order_details.*.quantity.required' => 'الكمية مطلوبة',
            'note'=>'الملاحظات مطلوبه'
        ];
    }

}

