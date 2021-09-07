<?php

namespace Blok\Repository\Traits;

use Exception;

trait DestroyApiMethod
{
    /**
     * Remove the specified resource
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function destroy($id)
    {
        try {
            return $this->model->delete($id);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
