<?php

namespace testapp\models\_abstract_models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class AbstractUsersModelEloquent extends EloquentModel
{
    protected $table = 'users';
}