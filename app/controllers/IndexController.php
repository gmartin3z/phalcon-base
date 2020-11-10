<?php

namespace App\Controllers;

use App\Validators\Index\UpdateValidator;

class IndexController extends ControllerBase
{
    public function publicAction()
    {
        return $this->view->render('index', 'public');
    }
}
