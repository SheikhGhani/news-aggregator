<?php
namespace App\Http\Services;

use Symfony\Component\HttpFoundation\Response;

class BaseService
{
    public array $response = [];

    public function sendSuccessResponseJson($result, $message, $code = Response::HTTP_OK, $totalRecords = null, $lastPage= null, $currentPage = null): \Illuminate\Http\JsonResponse
    {
        $this->response = [
            'success' => true,
            'message' => $message,
            'data'    => $result,
            'error' => null,
            'code' => $code,
            'total_records' => $totalRecords,
            'last_page' => $lastPage,
            'current_page' => $currentPage
        ];

        return response()->json($this->response, $code);
    }

    /**
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return \Illuminate\Http\Response
     */
    public function sendErrorResponseJson($error, array $errorMessages = [], int $code = Response::HTTP_NOT_FOUND): \Illuminate\Http\JsonResponse
    {
        $this->response = [
            'success' => false,
            'message' => $error,
            'data' => null,
            'error'=>null,
            'code' => $code
        ];

        if(!empty($errorMessages)){
            $this->response['error'] = $errorMessages;
        }

        return response()->json($this->response, $code);
    }
}
