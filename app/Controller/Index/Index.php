<?php
namespace App\Controller\Index;

use Julibo\Msfoole\ClientController as Controller;

class Index extends Controller
{
    public function init()
    {
    }

    public function health()
    {
        echo "hello world!";
    }

    public function index()
    {
        return time();
    }
}