<?php


namespace minipoBundle\Controller;


use Proxies\__CG__\minipoBundle\Entity\Commande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandeController extends Controller
{

    public function commandesCltAction()
    {
        $repo=$this->getDoctrine()->getManager()->getRepository(Commande::class);
        $list=$repo->myFindCmdByClt();
        return $this->render('@minipo/Commande/CommandesClt.html.twig',array('cmd'=>$list));
    }

}