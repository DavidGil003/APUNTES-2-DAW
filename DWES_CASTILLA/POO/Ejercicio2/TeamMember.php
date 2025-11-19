<?php

class TeamMember
{

    public string $name;
    public string $email;
    public string $role;

    public function __construct(string $name, string $email, string $role)
    {
        $this->name = $name;
        $this->email = $email;
        $this->role = $role;







    }



    public function getProfile(): string
    {
        return "[$this->role]" . "  " . $this->name . "  " . "(  $this->email)  " ;





    }


}



?>