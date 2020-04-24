<?php


namespace minipoBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function ProfileEmpAction(){

        return ($this->render('@minipo/RH/profile.html.twig'));
    }
}