<?php

namespace App\Controllers;

use Phalcon\Http\Response;

class ErrorsController extends ControllerBase
{
    public function show404Action()
    {
        $response = new Response();
        $response->setStatusCode(404);
        $response->send();
        return $this->view->render('errors', '404');
    }
}
