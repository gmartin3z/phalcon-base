<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Confirmation;

class FinishUpdatePasswordValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_f_upd_password_presence_new_password')
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_f_upd_password_presence_confirm_new_password'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'contrasenia',
            new Regex([
                'message' => $tr->_('v_f_upd_password_regex_new_password'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new Regex([
                'message' => $tr->_('v_f_upd_password_regex_confirm_new_password'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'contrasenia',
            new Confirmation([
                'message' => [
                    'contrasenia' => $tr->_('v_f_upd_password_confirmation_new_password')
                ],
                'with' => [
                    'contrasenia' => 'repetir_contrasenia'
                ]
            ])
        );
    }
}
