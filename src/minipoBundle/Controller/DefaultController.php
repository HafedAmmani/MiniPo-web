<?php

namespace minipoBundle\Controller;

use minipoBundle\Entity\Commande;
use minipoBundle\Entity\Facture;
use minipoBundle\Entity\Lignecommande;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    public function indexclientexterneAction()
    {
        //return $this->render('@minipo/Default/index.html.twig');
        return $this->redirectToRoute('minipo_affichage');
    }
    public function clientAction()
    {
        //return $this->render('@minipo/Default/indexclient.html.twig');
        return $this->redirectToRoute('minipo_allProduits');
    }
    public function employeAction()
    {
        return $this->render('@minipo/Default/indexemploye.html.twig');
    }
    public function adminAction()
    {
        return $this->render('@minipo/Default/indexadmin.html.twig');
    }
    public function blogAction()
    {
        return $this->render('@minipo/Default/indexBlog.html.twig');
    }


    /**
     * @Route("/", name="homepage")
     */
    public function indexAction($idFact)
    {
        $em=$this->getDoctrine()->getManager();
        $fact=$em->getRepository(Facture::class)->find($idFact);

        //=$em->getRepository(Commande::class)->findBy(array('idcmd'=>$idFact));
        $idCmd=$fact->getIdcmd();

        $repo=$this->getDoctrine()->getManager()->getRepository(Lignecommande::class);
        $lc=$repo->myFindLcByFact((string)$idFact,$idCmd);


        $snappy=$this->get('knp_snappy.pdf');

        $html =$this->renderView('default/PDF.html.twig',
            array('title' => 'Facture','lc'=>$lc,'f'=>$fact,'d'=>new \DateTime("now")));



        $filename = 'myFirstSnappyPDF';
        return new Response(
            $snappy->getOutputFromHtml($html),
            200,
            array(
                'Content-Type'          => 'application/pdf',
                'Content-Disposition'   => 'inline; filename="'.$filename.'.pdf"'
            )
        );
    }
    public function AboutAction(){

        return $this->render('@minipo/Default/AboutAs.html.twig');
}


}
