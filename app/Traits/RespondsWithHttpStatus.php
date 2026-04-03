<?php

namespace App\Traits;

trait RespondsWithHttpStatus
{
    protected function success($data = null, $message = 'Operation successful', $statusCode = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $statusCode);
    }

    protected function error($message = 'Operation failed', $statusCode = 400, $errors = null)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $statusCode);
    }

    protected function respondWithView($view, $data = [], $message = null, $type = 'success')
    {
        if ($message) {
            session()->flash($type, $message);
        }

        return view($view, $data);
    }

    protected function redirectWithSuccess($route, $message = 'Operation successful', $params = [])
    {
        return redirect()->route($route, $params)->with('success', $message);
    }

    protected function redirectWithError($route, $message = 'Operation failed', $params = [])
    {
        return redirect()->route($route, $params)->with('error', $message);
    }

    protected function backWithSuccess($message = 'Operation successful')
    {
        return back()->with('success', $message);
    }

    protected function backWithError($message = 'Operation failed')
    {
        return back()->with('error', $message);
    }
}
