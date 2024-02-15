<?php

namespace App\Controller;

use App\Entity\Forum;
use App\Form\ForumType;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use DateTime; 

#[Route('/forumfront')]
class ForumfrontController extends AbstractController
{
    #[Route('/', name: 'app_forumfront_index', methods: ['GET'])]
    public function index(ForumRepository $forumRepository): Response
    {
        return $this->render('forumfront/indexfront.html.twig', [
            'forums' => $forumRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_forumfront_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $forum->setCreatedAt(new DateTime()); // Set creation time
            
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->redirectToRoute('app_forumfront_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forumfront/newfront.html.twig', [
            'forumfront' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/editfront', name: 'app_forumfront_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ForumType::class, $forum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $forum->setUpdatedAt(new DateTime()); // Set update time

            $entityManager->flush();

            return $this->redirectToRoute('app_forumfront_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('forumfront/editfront.html.twig', [
            'forum' => $forum,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_forumfront_show', methods: ['GET'])]
    public function show(Forum $forum): Response
    {
        return $this->render('forumfront/showfront.html.twig', [
            'forum' => $forum,
        ]);
    }

    #[Route('/{id}', name: 'app_forumfront_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_forumfront_index', [], Response::HTTP_SEE_OTHER);
    }
}
