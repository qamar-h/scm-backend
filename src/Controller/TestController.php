<?php

namespace App\Controller;

use SCM\User\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'test')]
    public function index(UserRepository $repository): Response
    {   
        $user = $repository->find(1);
        $fullname = $user->getPerson()->getFullname();
        $age = $user->getPerson()->getAge();

        dd("Je m'appelle $fullname et j'ai $age an" . ($age > 1 ? "s" : ""));
    }
}
