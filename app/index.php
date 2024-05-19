<?php

use Illuminate\Database\Eloquent\Model;

require '../vendor/autoload.php';


// Модель пользователя
class User extends Model
{
    protected $table = 'users';
    public $timestamps = true;
}

// Получение пользователя с id 4
$user = User::where('id', 4)->first();

// Преобразование пользователя в массив
$userArray = $user ? $user->toArray() : null;

// Вывод массива пользователя
print_r($userArray);
