<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:5000',
            'category' => 'required|in:IR,Capture,Preset',
            'hardware_model' => 'required|string|max:150',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'preset_file' => 'nullable|file|max:51200',
            'dry_sample' => 'nullable|file|mimes:mp3,ogg,wav,m4a,flac,aac,mp4|max:30720',
            'wet_sample' => 'nullable|file|mimes:mp3,ogg,wav,m4a,flac,aac,mp4|max:30720',
            'tags' => 'nullable|string|max:500',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $category = $this->input('category');
            $file = $this->file('preset_file');

            if ($file && $category) {
                $ext = strtolower($file->getClientOriginalExtension());

                if ($category === 'IR' && $ext !== 'wav') {
                    $validator->errors()->add('preset_file', 'IR files must be in .wav format.');
                }

                if ($category === 'Capture' && !in_array($ext, ['nam', 'a2', 'zip'])) {
                    $validator->errors()->add('preset_file', 'Capture files must be .nam, .a2, or .zip format.');
                }

                if ($category === 'Preset') {
                    $forbidden = ['exe', 'bat', 'cmd', 'sh', 'php', 'js'];
                    if (in_array($ext, $forbidden)) {
                        $validator->errors()->add('preset_file', 'This file type is not allowed for Presets.');
                    }
                }
            }
        });
    }
}
