<?php

namespace markorm\tools;


class QueryLogs {

    public $queryes = [];

    function log(string $query){
        $this->queryes[] = $query;
    }

}