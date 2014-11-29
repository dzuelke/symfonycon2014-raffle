<?php

namespace Dzuelke\SymfonyCon2014\Bundle\RaffleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @Route("/raffles/")
     * @Template()
     */
    public function rafflesAction()
    {
        $raffles = array();
        foreach($this->get('doctrine_mongodb')->getManager()->getMetadataFactory()->getAllMetadata() as $meta) {
            if($meta->namespace == 'Dzuelke\SymfonyCon2014\Bundle\RaffleBundle\Document') $raffles[] = $meta->collection;
        }
        
        return array('raffles' => $raffles);
    }
    
    /**
     * @Route("/raffles/{raffle}")
     * @Method({"GET"})
     * @Template()
     */
    public function raffleAction($raffle)
    {
        $winners = $this->get('doctrine_mongodb')->getManager()->getRepository('DzuelkeSymfonyCon2014RaffleBundle:'. $raffle)->findByWon(true);
        foreach($winners as &$winner) {
            $winner = $winner->getName()?:$winner->getEmail();
        }
        
        return array('raffle' => $raffle, 'winners' => $winners, 'entrants' => $this->get('doctrine_mongodb')->getManager()->createQueryBuilder("DzuelkeSymfonyCon2014RaffleBundle:$raffle")->count()->getQuery()->execute());
    }
    
    /**
     * @Route("/raffles/{raffle}/winners/")
     * @Method({"POST"})
     * @Template()
     */
    public function drawAction($raffle) {
        $entrants = $this->get('doctrine_mongodb')->getManager()->getRepository('DzuelkeSymfonyCon2014RaffleBundle:'. $raffle)->findByWon(false);
        
        shuffle($entrants);
        
        $winner = $entrants[0];
        return new Response($this->get('twig')->resolveTemplate(array("DzuelkeSymfonyCon2014RaffleBundle:Default:draw.$raffle.html.twig", 'DzuelkeSymfonyCon2014RaffleBundle:Default:draw.html.twig'))->render(array('winner' => $winner, 'winner_name' => $winner->getName()?:$winner->getEmail(), 'raffle' => $raffle)));
    }
    
    /**
     * @Route("/raffles/{raffle}/winners/{id}")
     * @Method({"POST"})
     * @Template()
     */
    public function winAction($raffle, $id)
    {
        $dm = $this->get('doctrine_mongodb')->getManager();
        $entrant = $dm->getRepository('DzuelkeSymfonyCon2014RaffleBundle:'. $raffle)->findOneById($id);
        $entrant->setWon(true);
        $dm->flush();
        return array('raffle' => $raffle);
    }
}
