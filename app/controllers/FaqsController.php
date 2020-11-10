<?php

namespace App\Controllers;

use App\Validators\Faqs\UpdateValidator;

class FaqsController extends ControllerBase
{
    public function publicAction()
    {
        return $this->view->render('faqs', 'public');
    }
}
