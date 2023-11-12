<?php

namespace App\Services;

use App\Http\Requests\StoreFormRequest;
use App\Interfaces\FormServiceInterface;
use App\Models\Form;
use App\Models\FormField;
use Auth;
use DB;
use Exception;
use Log;
use Str;
use Throwable;

class FormService implements FormServiceInterface
{

    public function createForm(StoreFormRequest $form_data): bool
    {
        Log::info('Creating form');
        Log::debug($form_data->validated());
        try {
            do {
                $slug = Str::random(10);
            } while (Form::where('slug', $slug)->exists());
            DB::transaction(function () use ($form_data, $slug) {

                $form = Form::create($form_data->safe()->only('name', 'description') + [
                        'user_id' => Auth::guard('sanctum')->user()->id,
                        'slug' => $slug,
                    ]);

                foreach ($form_data->fields as $index => $field) {
                    Log::info('Creating fields $index');
                    Log::debug($field);
                    FormField::create([
                        'form_id' => $form->id,
                        'name' => $field['name'],
                        'type' => $field['type'],
                        'required' => $field['required'],
                        'options' => isset($field['options'])? json_encode($field['options'], JSON_THROW_ON_ERROR) : null,
                        'default' => $field['default'] ?? null,
                    ]);
                }
                return true;
            });

        } catch (Exception|Throwable $e) {
            Log::error($e->getMessage());
        }
        return false;
    }
}
