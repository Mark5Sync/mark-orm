<?php

namespace markorm\_system;


class ModelConfig {

    function __construct(
        public string $modelFolder,
        public string $modelNamespace,
        public string $abstractFolder,
        public string $abstractNamespace,
    )
    {}

}