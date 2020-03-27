<?php


namespace minipoBundle\Controller;


use minipoBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CommandeController extends Controller
{

    public function allCommandesAction()
    {
        $commandes=$this->getDoctrine()->getRepository(Commande::class)->findAll();
        return $this->render('@minipo/Commande/Commandes.html.twig',array('cmd'=>$commandes));
    }
    public function accepterCmdAction($id){
        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($id);

            $commandes->setEtatc("Acceptee");
            $em->flush();
            return $this->redirectToRoute('minipo_allCommandes');

    }
    public function refuserCmdAction($id){
        $em=$this->getDoctrine()->getManager();
        $commandes=$em->getRepository(Commande::class)->find($id);

        $commandes->setEtatc("Refusee");
        $em->flush();
        return $this->redirectToRoute('minipo_allCommandes');

    }

    public function commandesCltAction($id)
    {
        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $list=$repo->myFindCmdByClt($id);
        return $this->render('@minipo/Commande/CommandesClt.html.twig',array('cmd'=>$list));
    }
}