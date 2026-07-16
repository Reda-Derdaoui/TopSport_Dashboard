<?php

class TYPE_ABONNEMENT
{
    public string $Libelle_TAB;

    public function setLibelle(string $libel)
    {
        $this->Libelle_TAB = $libel;
    }
    public function getLibelle(): string
    {
        return $this->Libelle_TAB;
    }
}
