<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Confirmation;
use Phalcon\Validation\Validator\Identical;
use Gregwar\Captcha\PhraseBuilder as Phrase;

class FinishRecoverValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_f_rcv_presence_new_password')
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new PresenceOf([
                'message' => $tr->_('v_f_rcv_presence_confirm_password'),
            ])
        );

        $this->add(
            'captcha',
            new PresenceOf([
                'message' => $tr->_('v_f_rcv_presence_captcha'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'contrasenia',
            new Regex([
                'message' => $tr->_('v_f_rcv_regex_new_password'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'repetir_contrasenia',
            new Regex([
                'message' => $tr->_('v_f_rcv_regex_confirm_password'),
                'pattern' => '/^([A-Za-zÑñ0-9!$€#@.-_])*[A-Za-zÑñ0-9!$€#@.-_]+$/'
            ])
        );

        $this->add(
            'captcha',
            new Regex([
                'message' => $tr->_('v_f_rcv_regex_captcha'),
                'pattern' => '/^[A-Za-z0-9]+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'contrasenia',
            new Confirmation([
                'message' => [
                    'contrasenia' => $tr->_('v_f_rcv_confirmation_new_password')
                ],
                'with' => [
                    'contrasenia' => 'repetir_contrasenia'
                ],
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'captcha',
            new Identical([
                'message' => $tr->_('v_f_rcv_identical_recover_captcha'),
                'value' => $this->session->get('phrase')
            ])
        );
    }
}
