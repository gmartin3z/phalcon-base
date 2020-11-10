<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Uniqueness;
use App\Models\Admins as Admin;

class UpdateEmailValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'correo',
            new PresenceOf([
                'message' => $tr->_('v_upd_email_presence_new_email'),
            ])
        );

        $this->add(
            'contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_upd_email_presence_current_password')
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_upd_email_presence_confirm_password'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Regex([
                'message' => $tr->_('v_upd_email_regex_new_email'),
                'pattern' => '/^[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]+@([a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.])[a-zA-Z0-9Ññ][a-zA-Z]+)?+$/'
            ])
        );

        $this->add(
            'contrasenia',
            new Regex([
                'message' => $tr->_('v_upd_email_regex_current_password'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new Regex([
                'message' => $tr->_('v_upd_email_regex_confirm_password'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'contrasenia',
            new Confirmation([
                'message' => [
                    'contrasenia' => $tr->_('v_upd_email_confirmation_current_password'),
                ],
                'with' => [
                    'contrasenia' => 'repetir_contrasenia'
                ],
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Uniqueness([
                'message' => $tr->_('v_upd_email_uniqueness_new_email'),
                'model' => new Admin,
                'attribute' => 'email',
                'cancelOnFail' => true
            ])
        );
    }
}
