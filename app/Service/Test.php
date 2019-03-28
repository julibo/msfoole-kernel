<?php
namespace App\Service;


class Test extends BaseService
{
    protected function init()
    {
        // TODO: Implement init() method.
    }

    public function hello()
    {
        $this->log()->info('我来个去');
        return time();
    }
}