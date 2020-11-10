<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Identical;
use Gregwar\Captcha\PhraseBuilder as Phrase;

class RecoverValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'correo',
            new PresenceOf([
                'message' => $tr->_('v_rcv_prf_presence_current_email')
            ])
        );

        $this->add(
            'captcha',
            new PresenceOf([
                'message' => $tr->_('v_rcv_prf_presence_captcha'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Regex([
                'message' => $tr->_('v_rcv_prf_regex_current_email'),
                'pattern' => '/^[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]+@([a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.])[a-zA-Z0-9Ññ][a-zA-Z]+)?+$/'
            ])
        );

        $this->add(
            'captcha',
            new Regex([
                'message' => $tr->_('v_rcv_prf_regex_captcha'),
                'pattern' => '/^[A-Za-z0-9]+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'captcha',
            new Identical([
                'message' => $tr->_('v_rcv_prf_identical_captcha'),
                'value' => $this->session->get('phrase')
            ])
        );
    }
}
