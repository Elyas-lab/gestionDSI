<?php

namespace App\DTO;

enum TypeElement: string
{
    case Activite = 'activite';
    case Assistance = 'assistance';
    case Demande = 'demande';
    case Projet = 'projet';
    case Tache = 'tache';
    case Utilisateur = 'utilisateur';
}