<?php

namespace App\Service;

use Julibo\Msfoole\Config;
use Julibo\Msfoole\Facade\Log;
use Julibo\Msfoole\HttpClient;
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

    protected function serverCall($serverName, $uri, array $params)
    {
        $sideCar = Config::get('sidecar');
        $permit = $this->request->getHeader('permit');
        $identification = $this->request->getHeader('identification');
        $token = $this->request->getHeader('token');
        $cli = new HttpClient($sideCar['call_ip'], $sideCar['call_port'], $permit, $identification, $token);
        $uri = sprintf("/%s/%s", $serverName, $uri);
        $result = $cli->post($uri, $params);
        return $result;
    }

}