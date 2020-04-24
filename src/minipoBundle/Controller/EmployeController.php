<?php


namespace minipoBundle\Controller;


use Doctrine\DBAL\Types\DateTimeType;
use minipoBundle\Entity\Conge;
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
    public function Ajouter2Action(Request $request){
        $user = new User();
        $form = $this->createForm(EmployerType::class,$user);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted() and $form->isValid()){
            $user->setRoles(['employe']);
            $user->setDate(new \DateTime('now'));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('minipo_Afficher');
        }
        return $this->render('@minipo/RH/AjouterEmploye.html.twig',array('form'=> $form->createView()));
    }

    public function AjouterCongeAction(Request $request){

        $conge = new Conge();
        $id=$this->getUser()->getId();

        if($request->isMethod('POST')){
            $em = $this->getDoctrine()->getManager();
            $id = $em->getRepository('minipoBundle:User')->find($id);
            $conge->setType($request->get('type'));
            $conge->setDatedebut($request->get('date_debut'));
            $conge->setDatefin($request->get('date_fin'));
            $conge->setNbrjrs($request->get('nbrjrs'));
            $conge->setDescription($request->get('info_comp'));
            $conge->setEtat(false);
            $conge->setId($id);
            $em->persist($conge);
            $em->flush();
            return $this->redirectToRoute('minipo_AjouterConge');

        }
        return $this->render('@minipo/Employe/AjouterConge.html.twig');
    }
    public function AfficherCongeAction(){
        $id=$this->getUser()->getId();
        $listConge = $this->getDoctrine()
            ->getRepository(Conge::class)->findBy(array('id'=>$id));

        return $this->render('@minipo/Employe/AfficherConge.html.twig',array("listConge"=>$listConge));
    }
    public function AfficherEmployeEnCongeAction(){

        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listEmploye = $repository->findemployeenconge();
        $options = array(
            'code'   => 'string to encode',
            'type'   => 'qrcode',
            'format' => 'html',
        );
        $barcode =
            $this->get('skies_barcode.generator')->generate($options);
        return $this->render('@minipo/RH/AffichageemployeConge.html.twig',array("listemploye"=>$listEmploye,"barcode"=>$barcode));
    }
    public function DemandeCongeAction(){
        $em = $this->getDoctrine()->getManager();
        $Conge = $em->getRepository(Conge::class)
            ->findAll();
        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listConge = $repository->findCongeemploye();

        $repository=$this->getDoctrine()->getManager()->getRepository(User::class);
        $listCongeAccepter = $repository->findDemandeAccepter();

        return $this->render('@minipo/RH/DemandeConge.html.twig',array("listconge"=>$listConge,"Conge"=>$Conge,"listCongeAccepter"=>$listCongeAccepter));
    }
    public function AccepterCongeAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $emp = $em->getRepository(Conge::class)->find($id);

        $emp->setEtat(true);
        $em->flush();
        return $this->redirectToRoute("minipo_DemandeConge");
    }
}