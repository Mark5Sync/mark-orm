<?php

namespace testapp\models\_abstract_models;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use marksync\provider\NotMark;

#[NotMark]
class AbstractUsersModelEloquent extends EloquentModel
{
    protected $table = 'users';
}