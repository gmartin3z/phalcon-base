<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Identical;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Uniqueness;
use Gregwar\Captcha\PhraseBuilder as Phrase;
use App\Models\Admins as Admin;

class RegisterValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'alias',
            new PresenceOf([
                'message' => $tr->_('v_register_presence_alias')
            ])
        );

        $this->add(
            'correo',
            new PresenceOf([
                'message' => $tr->_('v_register_presence_email')
            ])
        );

        $this->add(
            'contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_register_presence_passsword')
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_register_presence_repeat_passsword')
            ])
        );

        $this->add(
            'captcha',
            new PresenceOf([
                'message' => $tr->_('v_register_presence_captcha'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'alias',
            new Regex([
                'message' => $tr->_('v_register_regex_alias'),
                'pattern' => '/^([A-Za-z]+\s)*[A-Za-zÑñ0-9]+$/'
            ])
        );

        $this->add(
            'correo',
            new Regex([
                'message' => $tr->_('v_register_regex_email'),
                'pattern' => '/^[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]+@([a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.])[a-zA-Z0-9Ññ][a-zA-Z]+)?+$/'
            ])
        );

        $this->add(
            'contrasenia',
            new Regex([
                'message' => $tr->_('v_register_regex_passsword'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new Regex([
                'message' => $tr->_('v_register_regex_repeat_passsword'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'captcha',
            new Regex([
                'message' => $tr->_('v_register_regex_captcha'),
                'pattern' => '/^[A-Za-z0-9]+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'captcha',
            new Identical([
                'message' =>  $tr->_('v_register_identical_captcha'),
                'value' => $this->session->get('phrase'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'contrasenia',
            new Confirmation([
                'message' => [
                    'contrasenia' => $tr->_('v_register_confirmation_password')
                ],
                'with' => [
                    'contrasenia' => 'repetir_contrasenia'
                ],
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'alias',
            new Uniqueness([
                'message' => $tr->_('v_register_uniqueness_alias'),
                'model' => new Admin,
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Uniqueness([
                'message' => $tr->_('v_register_uniqueness_email'),
                'model' => new Admin,
                'attribute' => 'email',
                'cancelOnFail' => true
            ])
        );
    }
}
