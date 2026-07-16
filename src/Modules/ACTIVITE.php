<?php

class ACTIVITE
{
    private String  $libelle_activite;

    public function setLibelle(string $libelle): void
    {
        $this->libelle_activite = $libelle;
    }
    public function getLibelle(): String
    {
        return $this->libelle_activite;
    }
}
