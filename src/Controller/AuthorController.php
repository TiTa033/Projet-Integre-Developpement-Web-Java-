<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
        #[Route('/showauthor/{var}', name: 'show_author')]
        public function showAuthor($var)
        {
            return $this->render("author/show.html.twig",array('nameAuthor'=>$var));
        }
    #[Route('/listauthor', name: 'list_author')]
    public function listAuthors()
        {
            $authors = array(

                array('id' => 1, 'username' => ' Victor Hugo','email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100),
    
        array ('id' => 2, 'username' => 'William Shakespeare','email'=>
    
    'william.shakespeare@gmail.com','nb_books' => 200),
    
        array('id' => 3, 'username' => ' Taha Hussein','email'=> 'taha.hussein@gmail.com','nb_books' => 300),
    
    );
return $this->render("author/list.html.twig",array("tabAuthors"=>$authors));
        }
    #[Route('/authordetails/{id}', name: 'author_details')]   
    public function details($id)
        {
            $authors = array(
                array('id' => 1, 'username' => 'Victor Hugo', 'email'=> 'victor.hugo@gmail.com', 'nb_books'=> 100),
                array('id' => 2, 'username' => 'William Shakespeare', 'email'=> 'william.shakespeare@gmail.com', 'nb_books' => 200),
                array('id' => 3, 'username' => 'Taha Hussein', 'email'=> 'taha.hussein@gmail.com', 'nb_books' => 300),
            );
    
            $author = null;
            foreach ($authors as $a) {
                if ($a['id'] == $id) {
                    $author = $a;
                    break;
                }
            }
    
            if (!$author) {
                throw $this->createNotFoundException('Author not found');
            }
    
            return $this->render('author/detail.html.twig', [
                'author' => $author,
            ]);
        }
        #[Route('/authorList', name: 'authors_list')]
        public function list(AuthorRepository $repository)
        {
            $authors=$repository->findall();
            return $this->render("author/listAuthors.html.twig",
        array('tabAuthors'=>$authors,
        'tabAuthors2'=>$repository->showAllAuthorsOrderByEmail()));
    
        }
        #[Route('/addAuthor', name: 'author_add')]
        public function addAuthor(ManagerRegistry $managerRegistry)
        {
            $author = new Author();
            $author->setUsername("apo");
            $author->setEmail("apo@gmail.com");
            $em = $managerRegistry->getManager();
            $em->persist($author);
            $em->flush();
            return $this->redirectToRoute("authors_list");
        }
        #[Route('/editAuthor/{id}', name: 'author_edit')]
        public function editAuthor(AuthorRepository $repository,$id,ManagerRegistry $managerRegistry)
        {
            
            $author = $repository->find($id);
            $author->setUsername("daughter");
            $author->setEmail("daughter@gmail.com");
            $em = $managerRegistry->getManager();

            $em->flush();
            return $this->redirectToRoute("authors_list");
        
        }
        #[Route('/deleteAuthor/{id}', name: 'author_delete')]
        public function deleteAuthor(AuthorRepository $repository,$id,ManagerRegistry $managerRegistry)
        {

            $author = $repository->find($id);
            $em = $managerRegistry->getManager();
            $em->remove($author);

            $em->flush();
            return $this->redirectToRoute("authors_list");
        
        }
        #[Route('/sortAuthor', name: 'author_sort')]
        public function sortAuthor(AuthorRepository $repository,$id,ManagerRegistry $managerRegistry)
        {

            $author = $repository->findBy($username);
            $em = $managerRegistry->getManager();
            $em->flush();
            return $this->redirectToRoute("authors_list");
        
        }


       
}       

    