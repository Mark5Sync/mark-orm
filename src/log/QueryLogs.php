<?php

namespace markorm\log;


class QueryLogs {

    public $queryes = [];

    function log(string $query){
        $this->queryes[] = $query;
    }

}