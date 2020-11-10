<?php

namespace App\Validators\Profile;

use Phalcon\Validation;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;
use Phalcon\Validation\Validator\Uniqueness;
use App\Models\Admins as Admin;

class UpdateAliasValidator extends Validation
{
    public function initialize()
    {
        $tr = $this->translator->getTranslation();

        $this->add(
            'alias',
            new PresenceOf([
                'message' => $tr->_('v_upd_alias_presence_new_alias'),
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'alias',
            new Regex([
                'message' => $tr->_('v_upd_alias_regex_new_alias'),
                'pattern' => '/^([A-Za-z]+\s)*[A-Za-zÑñ0-9]+$/',
                'cancelOnFail' => true
            ])
        );

        $this->add(
            'alias',
            new Uniqueness([
                'message' => $tr->_('v_upd_alias_uniqueness_new_alias'),
                'model' => new Admin,
                'attribute' => 'alias',
                'cancelOnFail' => true
            ])
        );
    }
}
