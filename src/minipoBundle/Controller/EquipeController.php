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
        $listAffectation = $this->getDoctrine()
            ->getRepository(Affectation::class)->findAll();


        $affectation = new Affectation();
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $nb=0;
            foreach ($listAffectation as $listaff){
                $nom=$request->get('equipe');
                if($listaff->getNomeq()==$nom){
                    $nb=$nb+1;}

            }

            foreach ($listEquipe as $listeq){
                if($request->get('equipe') == $listeq->getNomeq() && $nb < $listeq->getNombre()){
                    $affectation->setNom($request->get('nom'));
                    $affectation->setNomeq($request->get('equipe'));
                    $em->persist($affectation);
                    $em->flush();
                    $this->addFlash("success", "Success, l'employe est affecté");
                    return $this->redirectToRoute('minipo_Affecter');
                }elseif($nb > $listeq->getNombre()){
                    $this->addFlash("error", "Vous depassez le nombre maximum de ce equipe");
                }

            }
        }


        return ($this->render('@minipo/RH/Equipe/AffecterEquipe.html.twig',array("listeEmploye"=>$listEmploye,"listequipe"=>$listEquipe,"listaffectation"=>$listAffectation)));
    }
    public function EquipeAction(Request $request){
        $listEquipe = $this->getDoctrine()
            ->getRepository(Equipe::class)->findAll();
        $SommeEquipe=0;
        foreach($listEquipe as $elt) {
            $SommeEquipe=$SommeEquipe + $elt->getNombre();
        }

        $equipe = new Equipe();
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')){

            $equipe->setNomeq($request->get('nomeq'));
            $equipe->setNombre($request->get('nombre'));
            $em->persist($equipe);
            $em->flush();
            $this->addFlash("success", "Success, l'equipe est ajouté");
            return $this->redirectToRoute('minipo_Equipe');



        }

        return ($this->render('@minipo/RH/Equipe/GererEquipe.html.twig',array("listequipe"=>$listEquipe,"SommeEquipe"=>$SommeEquipe)));
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