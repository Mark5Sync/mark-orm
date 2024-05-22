<?php

namespace markorm\model;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use markorm\_system\ConnectionSource;
use marksync\provider\NotMark;

#[NotMark]
class Connection
{

    protected string $connectionProp;
    private ?EloquentModel $activeModel = null;


    function getModel(): EloquentModel
    {
        if ($this->activeModel)
            return $this->activeModel;

        /** @var ConnectionSource $connection */
        $connection = $this->{$this->connectionProp};
        $connection = $connection->createGlobalconnection();

        return $this->activeModel = $this->getEloquentModel();
    }


    protected function getEloquentModel(): EloquentModel
    {
        return new EloquentModel;
    }
}
