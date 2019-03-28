<?php
namespace App\Service;

use Swoole\Coroutine as co;

class Test extends BaseService
{
    protected function init()
    {
        // TODO: Implement init() method.
    }

    public function hello()
    {
        $this->log()->info('我来个去你的');
        return time();
    }
}