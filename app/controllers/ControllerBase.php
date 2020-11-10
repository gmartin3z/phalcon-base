<?php

namespace App\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Http\Response;
use HtmlSanitizer\Sanitizer as HtmlSanitizer;

class ControllerBase extends Controller
{
    public function translate($key){
        $tr = $this->translator->getTranslation();
        return $tr->_($key);
    }

    public function setLanguage()
    {
        $app_lang = $this->session->get('lang');

        if (!$app_lang) {
            $app_lang = strtolower(
                substr($this->request->getBestLanguage(), 0, 2)
            );

            $this->session->set('lang', $app_lang);
        }

        $app_lang_id;
        $time_lang;

        switch ($app_lang) {
            case 'es':
                $time_lang = 'es_ES';
                $app_lang_id = 2;
                break;
            case 'en':
                $time_lang = 'en_US';
                $app_lang_id = 1;
                break;
            default:
                $app_lang = 'en';
                $time_lang = 'en_US';
                $app_lang_id = 1;
                break;
        }

        $this->view->app_lang = $app_lang;
        $this->view->app_lang_id = $app_lang_id;
        $this->view->time_lang = $time_lang;
    }

    public function initialize()
    {
        $default_timezone = $this->config->application->defaultTimezone;
        date_default_timezone_set($default_timezone);
    }

    public function beforeExecuteRoute(Dispatcher $dispatcher)
    {
        $this->setLanguage();

        $maintenance = $this->config->status->maintenance;
        $signup = $this->config->application->allowSignup;

        if ($maintenance == true) {
            $response = new Response();
            $response->setStatusCode(503);
            $response->send();
            $this->view->render('errors', '503');
            return false;
        }

        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $profile = 'visitor';
        $admin_auth = $this->admin_auth->getIdentity();

        if ($admin_auth) {
            switch ($admin_auth['permission_id']) {
                case 1:
                    $profile = 'admin';
                    break;
                case 2:
                    $profile = 'limited';
                    break;
                default:
                    $profile = 'visitor';
                    break;
            }
        }

        $is_allowed = $this->acl->isAllowed($profile, $controller, $action);

        if ($is_allowed == false) {
            $this->flash->error($this->translate('not_allowed'));
            $this->response->redirect('inicio');
            return false;
        }

        if ($signup == false) {
            if ($controller == 'profile' && $action == 'register') {
                $this->flash->error($this->translate('registration_blocked'));
                return $this->response->redirect('inicio');
            }
        }
    }
}
