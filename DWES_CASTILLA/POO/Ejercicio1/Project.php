<?php

class Project
{

    public function __construct(
        public int $id,
        public string $name,
        public string $status = "Pending"
    ) {
    }




}

?>