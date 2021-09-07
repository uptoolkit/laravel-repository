<?php

namespace Blok\Repository\Traits;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait UpdateApiMethod
{
    /**
     * Update the specified resource
     *
     * @param \Illuminate\Http\Request $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function update(Request $request, $id)
    {
        try {
            return $this->model->update($request->all(), $id);
        } catch (ValidationException $e) {
            return response()->json($e->errors(), 422);
        } catch (Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
