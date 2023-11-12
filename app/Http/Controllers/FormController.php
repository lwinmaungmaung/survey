<?php

namespace App\Http\Controllers;

use App;
use App\Http\Requests\StoreFormRequest;
use App\Models\Form;
use App\Services\FormService;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use JsonException;
use Log;
use Throwable;
use Validator;
use View;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFormRequest $request, FormService $formService): ?JsonResponse
    {
        $fields = $request->validated(['fields']);
        foreach ($fields as $field) {
            $rule = [
                'name' => 'required|string',
                'type' => 'required|string',
                'required' => 'required|boolean',
                'options' => 'array',
                'default' => 'string',
            ];
            Validator::validate($field, $rule);
        }
        try {
            $url = $formService->createForm($request);
            if ($url) {
                return response()->json(['message' => 'Form created successfully.', 'url' => $url], 201);
            }
            return response()->json(['message' => 'Form cannot created.'], 400);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => "something went wrong, please refer to administrator."], 500);
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(Form $form)
    {
        $form->load('fields');
        abort_unless(View::exists('public.form.view'),400);
        return view('public.form.view',compact('form'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Form $form): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Form $form): void
    {
        //
    }

    /**
     * @throws Throwable
     */
    public function public_submit(Request $request, Form $form,FormService $formService)
    {
        try{
            $status = $formService->submitForm($request,$form);
            if($status){
                return response()->json(['message' => 'Form submitted successfully.'], 201);
            }
            return response()->json(['message' => 'Form cannot submitted.'], 400);
        }catch (Exception|JsonException|QueryException $e){
            Log::error($e->getMessage());
            throw_unless(App::environment('production'), $e);
            return response()->json(['message' => "something went wrong, please refer to administrator."], 500);
        }
    }
}
