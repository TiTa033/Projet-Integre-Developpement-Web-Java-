<?php

namespace App\Controller;

use App\Form\EditBookType;
use App\Form\BookType;
use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\BookRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
    /*#[Route('/addBook', name: 'addbook')]
        public function addBook(ManagerRegistry $managerRegistry)
        {
            $book = new Book();
            $book->setRef("bk004");
            $book->setTitle("The Book of mahdi2");
            $book->setPublished(true);
            $publicationDate = \DateTime::createFromFormat('Y/m/d', '2022/04/09');
            $book->setPublicationDate($publicationDate);
            $em = $managerRegistry->getManager();
            $em->persist($book);
            $em->flush();
            return new Response("ajout avec succés");
            return $this->redirectToRoute("book_list");
        }*/
   #[Route('/listBook', name: 'list_book')]
        public function list(BookRepository $repository)
        {
            
            $books=$repository->findall();
            return $this->render("book/listbooks.html.twig",
            array('tabBooks'=>$books,));
    
        }
   #[Route('/addBook', name: 'add_Book')]
        public function addBook(Request $request, ManagerRegistry $managerRegistry)
    {
        $book = new Book();
        $book->setPublished(true);
        $form = $this->CreateForm(BookType::class, $book);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {  
            $em=$managerRegistry->getManager();
            $nbBooks= $book->getAuthor()->getNbBooks();
            $book->getAuthor()->setNbBooks($nbBooks+1);
            $em->persist($book);
            $em->flush();
            return new Response("Done!");
        }

        return $this->renderForm("book/add.html.twig",
        array('formulaireBook'=>$form));
    }
    #[Route('/editBook/{ref}', name: 'edit_Book')]
    public function editBook($ref, Request $request, ManagerRegistry $managerRegistry, BookRepository $repository)
    {
        $book = $repository->findOneBy(['ref' => $ref]);
        $form = $this->createForm(EditBookType::class, $book);
        $form->handleRequest($request);
    
        if ($form->isSubmitted()) {
            $em = $managerRegistry->getManager();
            $em->flush();
            
            return new Response("Book updated successfully!");
        }
    
        return $this->renderForm("book/edit.html.twig", [
            'formulaireEditBook' => $form
        ]);
    }

    #[Route('/deleteBook/{ref}', name: 'delete_Book')]
    public function deleteBook($ref, ManagerRegistry $managerRegistry, BookRepository $repository)
    {
        $book = $repository->findOneBy(['ref' => $ref]);
        $em = $managerRegistry->getManager();
        $em->remove($book);
        $em->flush();
            
            return new Response("Book deleted successfully!");
    }
    #[Route('/showBook/{ref}', name: 'show_Book')]
    public function showBook($ref, BookRepository $repository)
    {
        $book = $repository->findOneBy(['ref' => $ref]);

            
        return $this->renderForm("book/show.html.twig", ['book' => $book]);
    }

    #[Route('/showauthorbooks/{id}', name: 'author_bookbyAuthor')]
    public function showauthorbooks(BookRepository $repository,$id)
    {
        return $this->render("book/showbookbyAuthor.html.twig",array('tabBooksA'=>$repository->showAllBooksOrderByAuthors($id)));

    }
    

}
