<?php

namespace App\Services;

class DateService
{

    /**
     * Calcule la différence en années entre une date donnée et la date actuelle.
     *
     * @param string $birthdate La date de naissance ou la date initiale au format 'Y-m-d'.
     * @return int La différence en années.
     */
    public static function calculateAge($birthdate)
    {
        $dateOfBirth = new \DateTime($birthdate);
        $currentDate = new \DateTime('now');
        return $dateOfBirth->diff($currentDate)->y;
    }

    /**
     * Catégorise l'âge dans l'un des trois groupes : enfant, jeune, adulte.
     *
     * @param int $age L'âge à catégoriser.
     * @return string La catégorie correspondante à l'âge.
     */
    public static function categorizeAge($age)
    {
        if ($age >= 0 && $age <= 12) {
            return 'enfant';
        } elseif ($age >= 13 && $age <= 18) {
            return 'jeune';
        } else {
            return 'adulte';
        }
    }
}
