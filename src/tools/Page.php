<?php

namespace markorm\tools;


class Page {

    function __construct(public int $index, public int $size, public ?int &$pages = null)
    {
        
    }

}