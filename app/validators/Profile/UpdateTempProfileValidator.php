<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Uniqueness;
use App\Models\Admins as Admin;

class UpdateTempProfileValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'correo',
            new PresenceOf([
                'message' => $tr->_('v_upd_temp_profile_presence_new_email'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Regex([
                'message' => $tr->_('v_upd_temp_profile_regex_new_email'),
                'pattern' => '/^[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]+@([a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.\-\_])*[a-zA-Z0-9Ññ]*([a-zA-Z0-9Ññ]+[\.])[a-zA-Z0-9Ññ][a-zA-Z]+)?+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'correo',
            new Uniqueness([
                'message' => $tr->_('v_upd_temp_profile_uniqueness_new_email'),
                'model' => new Admin,
                'attribute' => 'email',
                'cancelOnFail' => true
            ])
        );
    }
}
