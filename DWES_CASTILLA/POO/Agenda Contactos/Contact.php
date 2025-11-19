<?php

class Contact
{
    private string $name;
    private string $email;
    private string $phone;
    private string $avatarPath;
    private string $avatarName;
    public bool $favorite; 

    public function __construct(string $name, string $email, string $phone = '', string $avatarPath = '', string $avatarName = '')
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->avatarPath = $avatarPath;
        $this->avatarName = $avatarName;
        $this->favorite = false; 
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getAvatarPath(): string
    {
        return $this->avatarPath;
    }


    public function getFavorite(): bool{
        return $this->favorite;
    }
    public function getAvatarName(): string
    {
        return $this->avatarName;
    }

    public function toggleFavorite(): void
    {
        $this->favorite = !$this->favorite; 
    }

    public function getSummary(): string
    {
        $summary = "Nombre: " . $this->name . " | Email: " . $this->email;
        
        if (!empty($this->phone)) {
            $summary .= " | Teléfono: " . $this->phone;
        }
        
        
        return $summary;
    }
}

?>