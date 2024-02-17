<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
                // Handle exception if something goes wrong during file upload
                // e.g., display an error message to the user
            }
            
            // Update the 'imageFilename' property of the 'Annonce' entity
            $annonce->setImageFilename($newFilename);
        }
            $entityManager->persist($annonce);
            $entityManager->flush();

            return $this->redirectToRoute('app_annonce_indexfront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce_front/newfront.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_showfront', methods: ['GET'])]
    public function show(Annonce $annonce): Response
    {
        return $this->render('annonce_front/showfront.html.twig', [
            'annonce' => $annonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_editfront', methods: ['GET', 'POST'])]
public function edit(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
{
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
                // Handle exception if something goes wrong during file upload
                // e.g., display an error message to the user
            }

            // Delete the old image file, if it exists
            if ($annonce->getImageFilename()) {
                unlink($this->getParameter('images_directory').'/'.$annonce->getImageFilename());
            }

            // Update the 'imageFilename' property of the 'Annonce' entity
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


    #[Route('/{id}', name: 'app_annonce_deletefront', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annonce_indexfront', [], Response::HTTP_SEE_OTHER);
    }
}
