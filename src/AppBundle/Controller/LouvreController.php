<?php
/**
 * Created by PhpStorm.
 * User: stefa
 * Date: 16/10/2017
 * Time: 18:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Billet;
use AppBundle\Entity\Commande;
use AppBundle\Entity\Utilisateur;
use AppBundle\Form\BilletType;
use AppBundle\Form\CommandeType;
use AppBundle\Form\UtilisateurType;
use AppBundle\Repository\CommandesRepository;
use AppBundle\Service\EstDisponible;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\CalculerPrix;
use Symfony\Component\HttpFoundation\Response;

class LouvreController extends Controller
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/louvre")
     */
    public function accueilAction()
    {
        return $this->render(':louvre:accueil.html.twig');
    }

    /**
     * @Route("/louvre/selection")
     */
    public function selectionAction()
    {

        return $this->render(':louvre:selection.html.twig');
    }

    /**
     * @Route("/louvre/panier/utilisateur:{idUser}", name="panier")
     */
    public function panierAction(Request $request, $idUser)
    {
        $em = $this->getDoctrine()->getManager();
        $commande = new Commande();
        $utilisateur = $em->getRepository("AppBundle:Utilisateur")->find($idUser);
        $form = $this->createForm(CommandeType::class, $commande);

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $commande->setUtilisateur($utilisateur);
                $total = 0;
                foreach ($commande->getBillet() as $billet)
                {
                    $calculerPrix = new CalculerPrix();
                    $prix = $calculerPrix->prixBillet($billet);
                    $billet->setCommande($commande);
                    $em->persist($billet);
                    $total = $total + $prix;
                }
                $commande->setPrix($total);
                $em->persist($commande);
                $commandeRepo = $em->getRepository("AppBundle:Commande");
                $billetsDispo = $commandeRepo->countBillets($commande);
                $estDisponible = new EstDisponible();
                if ($estDisponible->billetsDispo($billetsDispo) == true && $estDisponible->test($commande->getDateBillet() == false))
                {
                    $em->flush();
                    $this->addFlash('success', 'Billet bien enregistré.');
                    return $this->redirectToRoute('recap_cmd', array('idCmd' => $commande->getId()));
                }
                else
                {
                    $infoDispo = $estDisponible->resteBillets($billetsDispo);
                    $this->addFlash('danger', $infoDispo);
                    return $this->render(':louvre:panier.html.twig', array('form' => $form->createView()));
                }
            }
        }
        return $this->render(':louvre:panier.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/louvre/info_facturation", name="info_fac")
     */
    public function infoFacturationAction(Request $request)
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if ($form->isValid())
            {
                $em = $this->getDoctrine()->getManager();
                $em->persist($utilisateur);
                $em->flush();
                return $this->redirectToRoute('panier', array('idUser' => $utilisateur->getId()));
            }
        }

        return $this->render(':louvre:infoFacturation.html.twig', array('form' => $form->createView()));
    }

    /**
     * @Route("/louvre/recap/commande:{idCmd}", name="recap_cmd")
     */
    public function recapAction(Request $request, $idCmd)
    {
        $commandeRepo = $this->em->getRepository('AppBundle:Commande');
        $commande = $commandeRepo->find($idCmd);
        if ($request->isMethod('POST'))
        {
            \Stripe\Stripe::setApiKey("sk_test_0qfLdJkutmeqc5EVVMWRQzI0");
            // Token is created using Checkout or Elements!
            // Get the payment token ID submitted by the form:
            $token = $request->request->get("stripeToken");
            $prix = $commande->caluclerPrixCentimes();
            // Charge the user's card:
            \Stripe\Charge::create(array(
                "amount" => $prix,
                "currency" => "eur",
                "description" => "Example charge",
                "source" => $token,
            ));
            return $this->render("louvre/paiement.html.twig");
        }
        return $this->render(':louvre:recapPanier.html.twig', array('commande' => $commande));
    }

    /**
     * @Route("/louvre/paiement")
     */
    public function paiementAction(Request $request)
    {

            return $this->render("louvre/paiement.html.twig");



    }


}