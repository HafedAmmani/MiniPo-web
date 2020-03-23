<?php


namespace minipoBundle\Controller;


use Doctrine\DBAL\Types\DateTimeType;
use minipoBundle\Entity\Affectation;
use minipoBundle\Entity\Equipe;
use minipoBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EquipeController extends Controller
{
    public function AffecterAction(Request $request){
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemploye();
        $listEquipe = $this->getDoctrine()
            ->getRepository(Equipe::class)->findAll();


        $affectation = new Affectation();
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $affectation->setNom($request->get('nom'));
            $affectation->setNomeq($request->get('equipe'));
            $em->persist($affectation);
            $em->flush();
            return $this->redirectToRoute('minipo_Affecter');
        }

        return ($this->render('@minipo/RH/Equipe/AffecterEquipe.html.twig',array("listeEmploye"=>$listEmploye,"listequipe"=>$listEquipe)));
    }
    public function EquipeAction(Request $request){
        $listEquipe = $this->getDoctrine()
            ->getRepository(Equipe::class)->findAll();

        $equipe = new Equipe();
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $equipe->setNomeq($request->get('nomeq'));
            $equipe->setNombre($request->get('nombre'));
            $em->persist($equipe);
            $em->flush();
            return $this->redirectToRoute('minipo_Equipe');
        }

        return ($this->render('@minipo/RH/Equipe/GererEquipe.html.twig',array("listequipe"=>$listEquipe)));
    }
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(Equipe::class)
            ->find($id);
        $em->remove($club);
        $em->flush();
        return $this->redirectToRoute("minipo_Equipe");
    }
    public function updateAction(Request $request , $id){
        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository(Equipe::class)->find($id);
        if($request->isMethod('POST')){
            $emp->setNomeq($request->get('nomeq'));
            $emp->setNombre($request->get('nombre'));
            $em->flush();
            return $this->redirectToRoute('minipo_Equipe');

        }
        return $this->render('@minipo/RH/Equipe/UpdateEquipe.html.twig', array('emp' =>$emp));
    }
}