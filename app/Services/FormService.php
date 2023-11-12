<?php

namespace App\Services;

use App\Http\Requests\StoreFormRequest;
use App\Interfaces\FormServiceInterface;
use App\Models\Form;
use App\Models\FormField;
use Auth;
use DB;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use Log;
use Str;
use Throwable;
use Validator;

class FormService implements FormServiceInterface
{

    public function createForm(StoreFormRequest $form_data): string | null
    {
        Log::info('Creating form');
        Log::debug($form_data->validated());
        try {
            do {
                $slug = Str::random(10);
            } while (Form::where('slug', $slug)->exists());
            return DB::transaction(static function () use ($form_data, $slug) {

                $form = Form::create($form_data->safe()->only('name', 'description') + [
                        'user_id' => Auth::guard('sanctum')->id(),
                        'slug' => $slug,
                    ]);
                foreach ($form_data->validated()['fields'] as $field) {
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
                $url =  route('public.form.show',$slug);
                Log::info("URL is : $url");
                return $url;
            });
        } catch (Exception|Throwable $e) {
            Log::error($e->getMessage());
        }
        return null;
    }

    /**
     * @throws JsonException
     */
    public function submitForm(Request $request, Form $form)
    {
        $form->load('fields');
        $fields = $form->fields;
        $validation = [];
        $data = [];
        foreach ($fields as $index => $field){

            $field_name= $field->type==='checkbox' ? $field->name.'.*' : $field->name;
            $data[$field_name] = array_values($request->except('_token'))[$index] ?? null;
            $validation[$field_name] = $field->required ? 'required' : 'nullable';
            if($field->type==='select'|| $field->type==='radio'|| $field->type==='checkbox'){
                $validation[$field_name] .= '|'.'in:'.implode(',', json_decode($field->options, false, 512, JSON_THROW_ON_ERROR));
            }
        }
        Validator::make($data,$validation);

        $form->responses()->create([
            'response' => $data
        ]);
        Log::info('Form submitted'.json_encode($request->except('_token'), JSON_THROW_ON_ERROR));
        return true;
    }
}
