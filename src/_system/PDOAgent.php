<?php

namespace markorm\_system;

interface PDOAgent {

    public function getConnection(): \PDO;

}