<?php
namespace testapp\_markers;
use marksync\provider\provider;
use testapp\models\UsersModel;
use testapp\models\_abstract_models\AbstractUsersModelEloquent;

/**
 * @property-read UsersModel $usersModel
 * @property-read AbstractUsersModelEloquent $abstractUsersModelEloquent

*/
trait models {
    use provider;

   function createUsersModel(): UsersModel { return new UsersModel; }
   function createAbstractUsersModelEloquent(): AbstractUsersModelEloquent { return new AbstractUsersModelEloquent; }

}