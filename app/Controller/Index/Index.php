<?php
/**
 * Created by PhpStorm.
 * User: carson
 * Date: 2019/3/19
 * Time: 11:27 PM
 */

namespace App\Controller\Index;

use Julibo\Msfoole\AloneController as Controller;
use Julibo\Msfoole\Captcha;

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
        echo time();
    }

    public function test()
    {
        $Captcha = new Captcha(['useZh'=>true, 'useImgBg'=>true]);
        $result = $Captcha->entry();
        var_dump($result);
    }
}