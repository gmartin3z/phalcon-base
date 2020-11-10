<?php

namespace App\Controllers;

use Phalcon\Http\Response as Response;
use Gregwar\Captcha\CaptchaBuilder as Captcha;

class CaptchaController extends ControllerBase
{
    public function generateCaptchaAction()
    {
        $this->view->disable();

        $captcha = new Captcha;
        $captcha->setDistortion(true);
        $captcha->setMaxBehindLines(50);
        $captcha->setMaxFrontLines(50);
        $captcha->setInterpolation(false);

        $this->session->set('phrase', $captcha->getPhrase());
        $captcha->build();

        $payload     = $captcha->output(10);
        $status      = 200;
        $description = 'Ok';
        $headers     = array();
        $content_type = 'image/jpeg';
        $content     = $payload;

        $response = new Response();
        $response->setStatusCode($status, $description);
        $response->setContentType($content_type);
        $response->setContent($content);

        foreach ($headers as $key => $value) {
           $response->setHeader($key, $value);
        }

        return $response;
    }
}
