<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Notification;
use minipoBundle\minipoBundle;
use minipoBundle\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class NotificationController extends Controller
{
    public function displayAction()
    {

        $Notification=$this->getDoctrine()->getManager()->getRepository(Notification::class)->findAll();
        foreach ($Notification as $elt){
            $id= $elt->getId();


        }

        return $this->render('@minipo/Reclamation/notification.html.twig',array('Notification'=>$Notification));
    }
    public function RemoveAction($id)
    {

        $em=$this->getDoctrine()->getManager();
        $notification=$em->getRepository('minipoBundle:Notification')->findOneBy(array('id'=>$id));
        $em->remove($notification);
        $em->flush();
        return $this->redirectToRoute("minipo_AfficherMesReclamationEmployes");
    }

    public function trouverEmployeAction(){
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemploye();
        $SommeSalaire=0;
        foreach($listEmploye as $elt) {
            $SommeSalaire=$SommeSalaire + $elt->getSalaire();
        }
        return ($this->render('@minipo/RH/afficherEmploye.html.twig',array("listeEmploye"=>$listEmploye , "SommeSalaire"=>$SommeSalaire)));
    }
}
