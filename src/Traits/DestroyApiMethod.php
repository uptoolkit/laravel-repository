<?php

namespace Blok\Repository\Traits;

use Exception;

trait DestroyApiMethod
{
    use Modelable;

    /**
     * Remove the specified resource
     *
     * @param $id
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response|mixed
     */
    public function destroy($id): mixed
    {
        try {
            return $this->model->delete($id);
        } catch (Exception $e) {
            return response($e->getMessage(), 500);
        }
    }
}
