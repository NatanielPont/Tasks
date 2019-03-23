<?php
namespace App\Http\Requests\Newsletters;
use Auth;
use Illuminate\Foundation\Http\FormRequest;
/**
 * Class PositionIndex.
 *
 * @package App\Http\Requests
 */
class NewsletterStore extends FormRequest
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
            'email' => 'required|string|email|max:255'
        ];
    }
}
