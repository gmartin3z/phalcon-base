<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Admins extends Model
{
    public $admin_id;
    public $alias;
    public $email;
    public $password;
    public $created_at;
    public $activated_at;
    public $updated_at;
    public $blocked_at;
    public $deleted_at;
    public $remember_session;
    public $session_expiration;
    public $permission_id;
    public $remaining_emails;
    public $block_emails_until;

    public function getSource()
    {
        return 'admins';
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
            'admin_id' => 'admin_id',
            'alias' => 'alias',
            'email' => 'email',
            'password' => 'password',
            'created_at'=> 'created_at',
            'activated_at' => 'activated_at',
            'updated_at' => 'updated_at',
            'blocked_at' => 'blocked_at',
            'deleted_at' => 'deleted_at',
            'remember_session' => 'remember_session',
            'session_expiration' => 'session_expiration',
            'permission_id' => 'permission_id',
            'remaining_emails' => 'remaining_emails',
            'block_emails_until' => 'block_emails_until'
        ];
    }
}
