<?php
namespace App\Interfaces;
use App\Http\Requests\StoreFormRequest;

interface FormServiceInterface
{
    public function createForm(StoreFormRequest $form_data):string|null;
}
