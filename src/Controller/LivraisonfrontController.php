<?php

namespace App\Controller;

use App\Entity\Livraison;
use App\Entity\Vehicle;
use App\Form\LivraisonType;
use App\Repository\LivraisonRepository;
use App\Repository\VehicleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/livraisonfront')]
class LivraisonfrontController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/', name: 'app_livraisonfront_index', methods: ['GET'])]
    public function index(LivraisonRepository $livraisonRepository): Response
    {
        return $this->render('livraisonfront/indexfront.html.twig', [
            'livraisons' => $livraisonRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_livraisonfront_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, VehicleRepository $vehicleRepository, LivraisonRepository $livraisonRepository): Response
    {
        $livraison = new Livraison();
        $disponibleVehicle = $vehicleRepository->findOneBy(['status' => 'disponible']);

        if (!$disponibleVehicle) {
            return $this->redirectToRoute('app_livraisonfront_index');
        }

        $livraison->setVehicle($disponibleVehicle);
        $livraison->setDate(new \DateTime());

        // Set the value of Cout based on the delivery type
        $type = $livraison->getType();
        if ($type === "Classic") {
            $livraison->setCout('10dt');
            $livraison->setDure('72hr');
        } elseif ($type === "Express") {
            $livraison->setCout('25dt');
            $livraison->setDure('24hr');
        } 
        //else {
            // Log the unexpected type
            //$this->logger->error("Unexpected delivery type: $type");
            
            // Throw an exception to halt execution and investigate the issue
            //throw new \Exception("Unexpected delivery type: $type");
        //}

        // Create the form
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle form submission
            $entityManager->persist($livraison);
            $entityManager->flush();

            return $this->redirectToRoute('app_livraisonfront_index', [], Response::HTTP_SEE_OTHER);
        }

        // Render the form
        return $this->renderForm('livraisonfront/newfront.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraisonfront_show', methods: ['GET'])]
    public function show(Livraison $livraison): Response
    {
        return $this->render('livraisonfront/showfront.html.twig', [
            'livraison' => $livraison,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_livraisonfront_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LivraisonType::class, $livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_livraisonfront_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('livraisonfront/editfront.html.twig', [
            'livraison' => $livraison,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_livraisonfront_delete', methods: ['POST'])]
    public function delete(Request $request, Livraison $livraison, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livraison->getId(), $request->request->get('_token'))) {
            $entityManager->remove($livraison);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_livraisonfront_index', [], Response::HTTP_SEE_OTHER);
    }
}
