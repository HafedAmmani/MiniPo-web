<?php

namespace minipoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexclientexterneAction()
    {
        return $this->render('@minipo/Default/index.html.twig');
    }
    public function clientAction()
    {
        return $this->render('@minipo/Default/indexclient.html.twig');
    }
    public function employeAction()
    {
        return $this->render('@minipo/Default/indexemploye.html.twig');
    }
    public function adminAction()
    {
        return $this->render('@minipo/Default/indexadmin.html.twig');
    }
    public function livreurAction()
    {
        return $this->render('@minipo/Livraison/affichLivraisonLivreur.html.twig');
    }
}
