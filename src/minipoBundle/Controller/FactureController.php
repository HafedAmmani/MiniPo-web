<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Facture;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FactureController extends Controller
{

    public function allFacturesAction()
    {
        $fact=$this->getDoctrine()->getRepository(Facture::class)->findAll();
        return $this->render('@minipo/Facture/Factures.html.twig',array('f'=>$fact));
    }

}