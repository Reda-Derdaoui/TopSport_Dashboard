<?php

class Entraineur
{

    private $Specialite;

    public function setSpecialite(string $specialite): void
    {
        $this->Specialite = $specialite;
    }
    public function getSpecialte(): string
    {
        return $this->Specialite;
    }
}
