<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Identical;
use Gregwar\Captcha\PhraseBuilder as Phrase;

class LoginValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'correo',
            new PresenceOf([
                'message' => $tr->_('v_login_presence_email')
            ])
        );

        $this->add(
            'contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_login_presence_passsword')
            ])
        );

        $this->add(
            'captcha',
            new PresenceOf([
                'message' => $tr->_('v_login_presence_captcha'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Regex([
                'message' => $tr->_('v_login_regex_email'),
                'pattern' => '/^[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]+@([a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.])[a-zA-Z0-9Ññ][a-zA-Z]+)?+$/'
            ])
        );

        $this->add(
            'contrasenia',
            new Regex([
                'message' => $tr->_('v_login_regex_passsword'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'captcha',
            new Regex([
                'pattern' => '/^[A-Za-z0-9]+$/',
                'message' => $tr->_('v_login_regex_captcha'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'captcha',
            new Identical([
                'value' => $this->session->get('phrase'),
                'message' => $tr->_('v_login_identical_captcha')
            ])
        );

        $this->add(
            'recordar_sesion',
            new Identical([
                'value' => 's',
                'message' => $tr->_('v_login_identical_remember_session'),
                'allowEmpty' => true
            ])
        );
    }
}
