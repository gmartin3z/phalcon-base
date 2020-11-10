<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Operations extends Model
{
    public $operation_id;
    public $operation_type_id;
    public $admin_id;
    public $email;
    public $token;
    public $ip;
    public $browser;
    public $expiration;

    public function getSource()
    {
        return 'operations';
    }

    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    public function columnMap()
    {
        return [
            'operation_id' => 'operation_id',
            'operation_type_id' => 'operation_type_id',
            'admin_id' => 'admin_id',
            'email' => 'email',
            'token'=> 'token',
            'ip' => 'ip',
            'browser' => 'browser',
            'expiration' => 'expiration'
        ];
    }
}
