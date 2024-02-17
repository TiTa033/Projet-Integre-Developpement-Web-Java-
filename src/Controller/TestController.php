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
    #[Route('/test1', name: 'test_1')]
    public function test()
    {
        return $this->render('test/test.html.twig');
    }
    #[Route('/test2', name: 'test_2')]
    public function test2()
    {
        return $this->render('test/testback.html.twig');
    }
}
