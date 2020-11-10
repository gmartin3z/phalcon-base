<?php

namespace App\Models;

use Phalcon\Mvc\Model;

class Permissions extends Model
{
    public $permission_id;
    public $description;
    public $created;
    
    public function getSource()
    {
        return 'permissions';
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
            'permission_id' => 'permission_id',
            'description' => 'description',
            'created' => 'created'
        ];
    }
}
