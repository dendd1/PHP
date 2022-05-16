<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyBibilController extends AbstractController
{
    #[Route('/my/bibil', name: 'app_my_bibil')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        //Получение книг конкретного пользователя
        $books = $doctrine->getRepository(Book::class)->findBy(['user' => $this->getUser()], ['id' => 'DESC']);
        return $this->render('my_bibil/index.html.twig', [
            'books' => $books,
        ]);
    }
}
