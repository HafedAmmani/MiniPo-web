<?php

namespace Tutorial\UserBundle\Controller;

use minipoBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
    * @Route("/admin" , name="admin_homepage")
     */
    public function adminAction(Request $request)
    {

        return $this->render('@minipo:Default:index.html.twig');
    }

}