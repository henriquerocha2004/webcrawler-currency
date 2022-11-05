<?php

namespace App\Http\Controllers;

use App\Http\Requests\CurrencyInformationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function getInformation(Request $request)
    {
        if (empty($request->all())) {
            return response("invalid data", Response::HTTP_BAD_REQUEST);
        }

        if (count($request->all()) > 1) {
            return response("invalid data", Response::HTTP_BAD_REQUEST);
        }

        $request->validate([
            'code' => 'string|min:3|max:3|nullable',
            'code_list' => 'array|nullable',
            'code_list.*' => 'string|min:3|max:3|nullable|alpha',
            'number' => 'integer|nullable',
            'number_lists' => 'array|nullable',
            'number_lists.*' => 'integer|digits:3|nullable'
        ]);



        return response("",Response::HTTP_OK);
    }
}
