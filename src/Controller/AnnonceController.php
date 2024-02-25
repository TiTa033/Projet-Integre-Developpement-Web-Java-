<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use App\Entity\ImageAnnonce;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $annonce->setDatePub(new \DateTime());
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
                    // Handle image upload
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();
        
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            
            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
  
            }
            
            $annonce->setImageFilename($newFilename);
        }
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET', 'POST'])]
    public function show(Request $request, Annonce $annonce, CommentRepository $commentRepository, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommentType::class, $comment);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setAnnonce($annonce);
            $comment->setCreationDate(new \DateTime());
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_show', ['id' => $annonce->getId()]);
        }

        $comments = $commentRepository->findBy(['annonce' => $annonce]);

        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AnnonceType::class, $annonce);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        
        /** @var UploadedFile $imageFile */
        $imageFile = $form->get('image')->getData();
        
        if ($imageFile) {
            $newFilename = uniqid().'.'.$imageFile->guessExtension();
            
            // Move the file to the directory where images are stored
            try {
                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );
            } catch (FileException $e) {
  
            }
            
            $annonce->setImageFilename($newFilename);
        }
        $entityManager->flush();

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('annonce/edit.html.twig', [
        'annonce' => $annonce,
        'form' => $form,
    ]);
}


#[Route('/{id}/delete', name: 'app_annonce_delete', methods: ['POST'])]
public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
{
    // Delete associated comments first
    $comments = $commentRepository->findBy(['annonce' => $annonce]);
    foreach ($comments as $comment) {
        $entityManager->remove($comment);
    }
    $entityManager->flush();

    // Now delete the Annonce record
    if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
        $entityManager->remove($annonce);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
}
}
