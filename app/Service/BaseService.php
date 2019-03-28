<?php

namespace App\Service;


use Julibo\Msfoole\Facade\Config;
use Julibo\Msfoole\Facade\Log;
use Julibo\Msfoole\Helper;
use Julibo\Msfoole\HttpClient;
use Julibo\Msfoole\HttpRequest;
use Julibo\Msfoole\Exception;

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
        if (isset($result['errCode']) && $result['errCode'] == 0 && isset($result['statusCode']) && $result['statusCode'] == 200) {
            if (Helper::isJson($result['data']) != false) {
                return json_decode($result['data'], true);
            } else {
                return $result['data'];
            }
        } else {
            throw new Exception('接口异常', 601);
        }
    }

}