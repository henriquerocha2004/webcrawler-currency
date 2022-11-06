<?php

namespace App\Http\Controllers;

use App\Services\CurrencyInformationService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class CurrencyController extends Controller
{

    public function __construct(
        private readonly CurrencyInformationService $currencyInformationService
    ){
    }

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

        try {
            $result = $this->currencyInformationService->search($request->all());
            return response()->json($result, Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error("GET_CURRENCY_INFORMATION_ERROR", [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            return response()->json(['error' => 'failed to search currency information'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
