<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthorRepository;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook', name: 'add_book')]
    public function addBook(Request  $request,ManagerRegistry  $managerRegistry)
    {
        $book = new Book();
        $form= $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        $book->setPublished(true);
        if($form->isSubmitted()){
            $em= $managerRegistry->getManager();
            //$nbBooks= $book->getAuthor()->getNbBooks();
            //$book->getAuthor()->setNbBooks($nbBooks+1);
            $em->persist($book);
            $em->flush();
            //var_dump($nbBooks).die();
            return  new Response("Done!");
        }
        return $this->renderForm("book/add.html.twig",
        array('formulaireBook'=>$form));
    }
    #[Route('/deleteBook/{ref}',name: 'deleteBook')]
    public function deleteBook($ref,BookRepository $repo, ManagerRegistry $manager){
        $book = $repo->findOneBy(['ref' => $ref]);
        $em=$manager->getManager();
        $em->remove($book);
        $em->flush();
        return $this->redirectToRoute('list_book');
    }
    #[Route('/editBook/{ref}',name:'editBook')]
    public function editBook($ref,BookRepository $repo,ManagerRegistry $manager,Request $request){
        $book=$repo->find($ref);
        $form=$this->createForm(BookType::class,$book);
        $form->add('Save',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()) {
            $em = $manager->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('list_book');

        }
        return $this->render('book/editBook.html.twig',['form'=>$form->createView()]);

    }
    #[Route('/showDetails/{ref}',name:'showDetails')]
    public function showDetails($ref,BookRepository $repo){
        $book=$repo->findOneBy(['ref' => $ref]);
        return $this->render('book/showDetails.html.twig',['book'=>$book]);

    }
    #[Route('/listBook', name: 'list_book')]
    public function listBook(BookRepository  $repository)
    {
        return $this->render("book/list.html.twig",
            array('tabBooks'=>$repository->findAll())
        );
    }
    
    #[Route('/showbookbyauthor/{id}', name: 'ordre_id')]
    public function showbookbyauthor(BookRepository  $repository ,$id)
    {
        
        return $this->render("book/showbookbyauthor.html.twig", ["tabBooksA" => $repository-> findBooksByAuthor($id)]);
    }
    
}
