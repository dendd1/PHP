<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\AddBookType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RemoveBookController extends AbstractController
{
    #[Route('/remove/book/{id}', name: 'app_remove_book')]
    public function index(int $id, ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_home');
        }
        $book_from_form = new Book();
        //Получаем старую версию книги
        $old_book = $doctrine->getRepository(Book::class)->findOneId($id);
        $form = $this->createForm(AddBookType::class, $book_from_form, [
            'action' => $this->generateUrl('app_add_book')
        ]);
        //Заполняем новыми данными форму
        $form->get('name')->setData($old_book[0]["name"]);
        $form->get('author')->setData($old_book[0]["author"]);
        $form->get('dateRead')->setData(new \DateTime($old_book[0]["date_read"]));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bookFile = $form->get('file')->getData();
            if ($bookFile) {
                $originalFilename = pathinfo($bookFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bookFile->guessExtension();
                try {
                    $bookFile->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                //Добавление картинки в директорию
                $book_from_form->setFile('/uploads/' . $newFilename);
            }
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
                try {
                    $pictureFile->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {}
                //Добавление картинки в директорию
                $book_from_form->setPicture('/uploads/' . $newFilename);
            }
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
            $book_from_form->setUser($this->getUser());
            $em = $doctrine->getManager();
            $newBook = $doctrine->getRepository(Book::class)->findOneBy(['id' => $id]);
            $newBook
                ->setPicture($book_from_form->getPicture())
                ->setFile($book_from_form->getFile())
                ->setUser($book_from_form->getUser())
                ->setDateRead($book_from_form->getDateRead())
                ->setAuthor($book_from_form->getAuthor())
                ->setName($book_from_form->getName());
            $em->persist($newBook);
            $em->flush();
            return $this->redirectToRoute('app_home');
        }
        return $this->render('add_book/index.html.twig', [
            'formBook' => $form->createView(),
        ]);
    }
}
