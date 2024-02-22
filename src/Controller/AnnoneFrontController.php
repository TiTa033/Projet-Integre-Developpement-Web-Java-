<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\Comment;
use App\Entity\ImageAnnonce;
use App\Form\AnnonceType;
use App\Form\CommentType;
use App\Form\ReplyType;
use App\Repository\AnnonceRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile; // Import UploadedFile class
use Doctrine\Common\Collections\ArrayCollection;




#[Route('/annoncefront')]
class AnnonceFrontController extends AbstractController
{
    #[Route('/', name: 'app_annonce_indexfront', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce_front/indexfront.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_annonce_newfront', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $annonce = new Annonce();
        $annonce->setDatePub(new \DateTime());
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFiles = $form->get('images')->getData();
        
            foreach ($imageFiles as $imageFile) {
                if ($imageFile instanceof UploadedFile) {
                    $newFilename = uniqid().'.'.$imageFile->guessExtension();
        
                    try {
                        $imageFile->move(
                            $this->getParameter('images_directory'),
                            $newFilename
                        );
                        // Store image filename in entity
                        $image = new ImageAnnonce();
                        $image->setFilename($newFilename);
                        $annonce->addImage($image);
                    } catch (FileException $e) {
                        // Handle error
                    }
                }
            }
            // Persist and flush the entity
            $entityManager->persist($annonce);
            $entityManager->flush();
        
            return $this->redirectToRoute('app_annonce_indexfront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_front/newfront.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_showfront', methods: ['GET', 'POST'])]
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

            return $this->redirectToRoute('app_annonce_showfront', ['id' => $annonce->getId()]);
        }

        $comments = $commentRepository->findBy(['annonce' => $annonce]);

        return $this->render('annonce_front/showfront.html.twig', [
            'annonce' => $annonce,
            'comments' => $comments,
            'commentForm' => $commentForm->createView(),
        ]);
    }


    #[Route('/{id}/edit', name: 'app_annonce_editfront', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {

                }

                if ($annonce->getImageFilename()) {
                    unlink($this->getParameter('images_directory').'/'.$annonce->getImageFilename());
                }

                $annonce->setImageFilename($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_indexfront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_front/editfront.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/delete', name: 'app_annonce_deletefront', methods: ['POST'])]
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
    
        return $this->redirectToRoute('app_annonce_indexfront', [], Response::HTTP_SEE_OTHER);
    }
}




