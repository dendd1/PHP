<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        //Получение списка всех книг в порялке по убыванию даты прочтения
        $books = $doctrine->getRepository(Book::class)->findBy([], ['id' => 'DESC']);
        return $this->render('home/index.html.twig', [
            'books' => $books,
        ]);
    }
}
