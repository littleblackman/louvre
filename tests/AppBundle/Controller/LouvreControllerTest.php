<?php
/**
 * Created by PhpStorm.
 * User: stefa
 * Date: 14/01/2018
 * Time: 07:52
 */

namespace Tests\AppBundle\Controller;


use AppBundle\Controller\LouvreController;
use AppBundle\Entity\Billet;
use AppBundle\Service\CalculerPrix;
use AppBundle\Service\EstDisponible;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LouvreControllerTest extends WebTestCase
{
    private $client;
    private $calculerPrix;
    private $billet;

    const CATEGORIE_BEBE = array('age' => 4, 'prix' => 0, 'type' => 'Gratuit');
    const CATEGORIE_SENIOR = array('age' => 60, 'prix' => 12, 'type' => 'Senior');
    const CATEGORIE_REDUIT = array('prix' => 10, 'type' => 'Réduit');
    const CATEGORIE_ENFANT = array('age' => 12, 'prix' => 8, 'type' => 'Enfant');
    const CATEGORIE_NORMAL = array('prix' => 16, 'type' => "Plein Tarif");

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->client = static::createClient();
        $this->calculerPrix = new CalculerPrix();
        $this->billet = new Billet();
    }

    public function testInfoFacturationAction()
    {
        $this->client->request('GET', '/louvre/info_facturation');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }


    public function testPrixBebe()
    {
        $billetTest = $this->billet
            ->setDemiJournee(false)
            ->setDateNaissance(new \DateTime("2015-11-01"))
            ->setTarifReduit(false)
        ;
        $this->calculerPrix->prixBillet($billetTest);
        $prix = $billetTest->getPrix();
        $this->assertEquals(self::CATEGORIE_BEBE['prix'], $prix);
    }

    public function testDispoBillets()
    {
        $nbBillets = 1001;
        $dispo = new EstDisponible();
        $test = $dispo->billetsDispo($nbBillets);

        $this->assertEquals(false, $test);
    }

    public function testMailIsSentAndContentIsOk()
    {
        $client = static::createClient();

        // enables the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/path/to/above/action');

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');

        // checks that an email was sent
        $this->assertSame(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
        $this->assertSame('Hello Email', $message->getSubject());
        $this->assertSame('send@example.com', key($message->getFrom()));
        $this->assertSame('recipient@example.com', key($message->getTo()));
        $this->assertSame(
            'You should see me from the profiler!',
            $message->getBody()
        );
    }



}