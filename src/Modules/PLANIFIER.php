<?php

class PLANIFIER
{
    public $date_activite;
    public $heure_activite;
    public $jour_activite;

    public function get_Activite()
    {
        $activite = new ACTIVITE();
        return $activite;
    }

    public function get_planing()
    {
        $planing = new PLANING();
        return $planing;
    }
}
