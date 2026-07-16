<?php


class Responsable
{
    private $login;
    private $password;

    public function setLogin($login): void
    {
        $this->login = $login;
    }
    public function getLogin(): String
    {
        return $this->login;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }
    public function getPassword(): String
    {
        return $this->password;
    }
}
