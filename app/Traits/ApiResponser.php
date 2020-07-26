<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

trait ApiResponser
{

    // return auth response data
    protected function sendAuthResult($message, $data, $error, $code, $status = true)
    {
        $result = [
            'status' => $status,
            'code' => $code,
            'data' => $data,
            'message' => $message,
            'error' =>  $error,
        ];
        return response()->json($result);
    }

    // handle all responses for multiple data
    protected function sendResult($message, $data, $errors = [], $status = true)
    {
        $errorCode = $status ? 200 : 422;
        $data = new Collection($data);

        if ($data->isEmpty() or $data === null) {
            $data = null;
        } else {
            // $data = $this->filterData($data);
            $data = $this->sortData($data);
            $data = $this->paginateData($data);
        }
        $result = [
            "message" => $message,
            "status" => $status,
            "data" => $data,
            "errors" => $errors
        ];
        return response()->json($result, $errorCode);
    }

    // handle single data rsponse
    protected function sendOne($message, $data, $errors = [], $status = true)
    {
        $errorCode = $status ? 200 : 422;
        if ($data == '' || $data == null) {
            $data = '';
        } else {
            $result = [
                "message" => $message,
                "status" => $status,
                "data" => $data,
                "errors" => $errors
            ];
        }
        return response()->json($result, $errorCode);
    }

    // Send Error Response
    protected function SendExceptionErr($errorMessage = [], $code)
    {
        return response()->json($errorMessage, $code);
    }

    // sort data by something
    protected function sortData($data)
    {
        // check if there is sort_by query in the request
        if (request()->has('sort_by')) {
            $attribute = request()->sort_by; // get attribute of sort_by
            $data = $data->sortBy->{$attribute};
        }
        return $data;
    }

    // filter data comming from request
    protected function filterData($data)
    {
        // get the query from the request and get the attribute with value
        // ex => http://127.0.0.1:8000/api/users?name=test
        // $query = name  & $value = test
        foreach (request()->query() as $query => $value) {
            if (isset($query, $value)) {
                // dd(isset($query, $value));
                $data = $data->whereIn($query, $value);
            }
        }
        return $data;
    }

    // paginate data
    protected function paginateData($data)
    {
        // rules for per page
        $rules = [
            'per_page' => 'integer|min:1|max:50'
        ];
        // to validate we are in trait not controller, so should use validator from Facades class
        Validator::validate(request()->all(), $rules);
        $perPage = 15;  // number of rows each page
        // check if there any per page number from user
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }
        $currentPage = LengthAwarePaginator::resolveCurrentPage(); // get the current page
        // data per page => start by zero as any array
        $result = $data->slice(($currentPage - 1) * $perPage, $perPage)->values();
        // make the pagination
        $paginated = new LengthAwarePaginator($result, $data->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        // when we resole the "path" parameter, the parameter current page => will ignore the other parameter like sort and filter
        $paginated->appends(request()->all());
        return $paginated;
    }
}
