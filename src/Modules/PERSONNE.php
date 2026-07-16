<?php

class Personne
{
    private String $nom;
    protected String $prenom;
    private String $tele;
    private DateTime $dateNaissance;

    public function setNom($nom): void
    {
        $this->nom = $nom;
    }
    public function getNom(): String
    {
        return $this->nom;
    }

    public function setPrenom($prenom): void
    {
        $this->prenom = $prenom;
    }
    public function getPrenom(): String
    {
        return $this->prenom;
    }

    public function setTele($tele): void
    {
        $this->tele = $tele;
    }
    public function getTele(): String
    {
        return $this->tele;
    }

    public function setDateN(DateTime $Date): void
    {
        $this->dateNaissance = $Date;
    }
    public function getDateN(): DateTime
    {
        return $this->dateNaissance;
    }
}
