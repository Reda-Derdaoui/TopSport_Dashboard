<?php

class PLANING
{
    private string $libelle_planning;

    public function setLibelle(string $libelle):void
    {
        $this->libelle_planning = $libelle;
    }
    public function getLibelle():string {
        return $this->libelle_planning; 
    }
}
