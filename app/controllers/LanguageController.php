<?php

namespace App\Controllers;

use Phalcon\Http\Response as Response;
use Gregwar\Captcha\CaptchaBuilder as Captcha;

class LanguageController extends ControllerBase
{
    public function changeAction()
    {
        $this->view->disable();

        $filters = array('trim', 'striptags', 'lower');

        $lang = $this->request->getPost('lang', $filters);

        switch ($lang) {
            case 'es':
                $lang = 'es';
                break;
            case 'en':
                $lang = 'en';
                break;
            default:
                $lang = 'en';
        }

        $this->session->set('lang', $lang);
        return $this->response->redirect($this->request->getHTTPReferer());
    }
}
