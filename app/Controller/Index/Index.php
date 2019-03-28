<?php
namespace App\Controller\Index;

use Julibo\Msfoole\AloneController as Controller;
use App\Service\Test as TestService;

class Index extends Controller
{
    /**
     * @var
     */
    private $service;

    public function init()
    {
        $this->service = new TestService($this->request);
    }

    public function health()
    {
        echo "hello world!";
    }

    public function index()
    {
        return time();
    }

    public function test()
    {
        return ['result'=>[], 'pageSize'=>10, 'pageNo'=>1, 'total'=>0];
    }

    public function hello()
    {
        $result = $this->service->hello();
        return $result;
    }

}