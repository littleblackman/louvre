<?php
/**
 * Created by PhpStorm.
 * User: stefa
 * Date: 10/12/2017
 * Time: 18:34
 */

namespace AppBundle\Service;


use AppBundle\AppBundle;
use AppBundle\Entity\Billet;
use AppBundle\Entity\Commande;
use AppBundle\Repository\BilletsRepository;
use AppBundle\Repository\CommandesRepository;
use Symfony\Component\HttpKernel\Tests\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;


use AppBundle\Entity\Utilisateur;
use AppBundle\Form\BilletType;
use AppBundle\Form\CommandeType;
use AppBundle\Form\UtilisateurType;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Service\CalculerPrix;
use Symfony\Component\HttpFoundation\Response;


class EstDisponible
{
    const BILLET_MAX = 1000;

    public function billetsDispo($nbBillets)
    {
        if ($nbBillets >= self::BILLET_MAX)
        {
            return false;
        }
        return true;
    }

    public function resteBillets($commandes)
    {
        if ((int)$commandes >= self::BILLET_MAX)
        {
            return "Désoler mais, il ne reste plus de billets pour cette date";
        }
        $reste = self::BILLET_MAX - (int)$commandes;
        return "Désoler mais, il ne reste que $reste billets";
    }

    public function test($key)
    {
        $joursFeries = new JoursFeries();
        if (array_search($key, $joursFeries->jours_feries()))
        {
            return "c'est pas cool!";
        }
        return "ok c'est bon!";
    }

}