<?php

class TYPE_ACTIVITE
{
    private string $libelTAC;

    public function setTAC(string $libelle): void
    {

        $this->libelTAC = $libelle;
    }

    public function getTAC(): string
    {
        return $this->libelTAC;
    }
}
