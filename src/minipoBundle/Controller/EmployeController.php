<?php


namespace minipoBundle\Controller;


use Doctrine\DBAL\Types\DateTimeType;
use minipoBundle\Entity\User;
use minipoBundle\Form\EmployerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmployeController extends Controller
{
    public function trouverEmployeAction(){
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemploye();
        $SommeSalaire=0;
        foreach($listEmploye as $elt) {
            $SommeSalaire=$SommeSalaire + $elt->getSalaire();
        }
        return ($this->render('@minipo/RH/afficherEmploye.html.twig',array("listeEmploye"=>$listEmploye , "SommeSalaire"=>$SommeSalaire)));
    }
    public function editAction($id,Request $request){
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(User::class)
            ->find($id);
        $form = $this->createForm(EmployerType::class,$club);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $em->flush();
            return $this->redirectToRoute('minipo_Afficher');
        }
        return $this->render('@minipo/RH/editEmploye.html.twig',array('f'=> $form->createView()));
    }

    public function updateAction(Request $request , $id){
        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository(User::class)->find($id);
        if($request->isMethod('POST')){
            $emp->setLastname($request->get('nom'));
            $emp->setFirstname($request->get('prenom'));
            $emp->setAdresse($request->get('adresse'));
            $emp->setEmail($request->get('email'));
            $emp->setTel($request->get('tel'));
            $emp->setSalaire($request->get('salaire'));
            $emp->setDate($request->get(DateTimeType::class));
            $em->flush();
            return $this->redirectToRoute('minipo_Afficher');

        }
        return $this->render('@minipo/RH/updateEmploye.html.twig', array('emp' =>$emp));
    }
    public function deleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $club = $em->getRepository(User::class)
            ->find($id);
        $em->remove($club);
        $em->flush();
        return $this->redirectToRoute("minipo_Afficher");
    }
    public function AjouterAction(Request $request){
        $user = new User();
        $em = $this->getDoctrine()->getManager();
        if($request->isMethod('POST')){
            $user->setLastname($request->get('nom'));
            $user->setFirstname($request->get('prenom'));
            $user->setAdresse($request->get('adresse'));
            $user->setEmail($request->get('email'));
            $user->setTel($request->get('tel'));
            $user->setSalaire($request->get('salaire'));
            $user->setRoles(['employe']);
            $user->setDate(new \DateTime('now'));
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('minipo_Afficher');

        }


        return $this->render('@minipo/RH/AjouterEmploye1.html.twig');
    }
}