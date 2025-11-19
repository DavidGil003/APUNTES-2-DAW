<?php

class Task
{

    public function __construct(
        public int $id,
        private bool $complete = false
    ) {
    }


    public function completeTask(): void
    {




        $this->complete = true;
    }

    public function getStatus(): string
    {



        if ($this->complete) {
            return "Completed";


        } else {

            return "Pending";
        }

    }
}












?>