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

class AddBookController extends AbstractController
{
    #[Route('/add/book', name: 'app_add_book')]
    public function index(ManagerRegistry $doctrine, Request $request, SluggerInterface $slugger): Response
    {
        //Проверка авторизации
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $book_for_form = new Book();
        //Создание формы
        $form = $this->createForm(AddBookType::class, $book_for_form, [
            'action' => $this->generateUrl('app_add_book')
        ]);
        $form->handleRequest($request);

        //Сабмит формы
        if ($form->isSubmitted() && $form->isValid()) {
            $bookFile = $form->get('file')->getData();
            if ($bookFile) {//Загрузка файла
                $originalFilename = pathinfo($bookFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $bookFile->guessExtension();
                try {
                    $bookFile->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                //Сохранение файла в директорию
                $book_for_form->setFile('/uploads/' . $newFilename);
            }
            $pictureFile = $form->get('picture')->getData();
            if ($pictureFile) {//Загрузка картинки
                $originalFilename = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $pictureFile->guessExtension();
                try {
                    $pictureFile->move(
                        $this->getParameter('uploads'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                //Сохранение файла в директорию
                $book_for_form->setPicture('/uploads/' . $newFilename);
            }
            $book_for_form->setUser($this->getUser());
            $em = $doctrine->getManager();
            $em->persist($book_for_form);
            $em->flush();
            return $this->redirectToRoute('app_home');

        }
        return $this->render('add_book/index.html.twig', [
            'formBook' => $form->createView()
        ]);
    }
}

