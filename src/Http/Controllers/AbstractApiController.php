<?php

namespace Blok\Repository\Http\Controllers;

use App\Http\Controllers\Controller;
use Blok\Repository\Contracts\ApiControllerContract;
use Blok\Repository\Traits\Modelable;

/**
 * Class AbstractApiController
 *
 * Default route controller to don't rewriting the same things again and again
 *
 * @package App\Http\Controllers
 */
abstract class AbstractApiController extends Controller implements ApiControllerContract
{
    use Modelable;
}
