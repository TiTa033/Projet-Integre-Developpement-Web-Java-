<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(): Response
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
    #[Route('/test1', name: 'test')]
    public function test()
    {
        return $this->render('test/test.html.twig'); 
    }
    #[Route('/testfront', name: 'testfr')]
    public function testfront()
    {
        return $this->render('test/testfront.html.twig'); 
    }
}
