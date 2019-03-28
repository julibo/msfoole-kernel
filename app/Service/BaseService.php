<?php

namespace App\Service;

use Julibo\Msfoole\Facade\Log;
use Julibo\Msfoole\HttpRequest;

abstract class BaseService
{
    /**
     * @var
     */
    protected $request;

    /**
     * BaseService constructor.
     * @param HttpRequest $request
     */
    public function __construct(HttpRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @return mixed
     */
    abstract protected function init();

    /**
     * @return mixed
     */
    final protected function log()
    {
        return Log::setEnv($this->request);
    }

}