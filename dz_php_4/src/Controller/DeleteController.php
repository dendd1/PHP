<?php

namespace App\Controller;

use App\Entity\Book;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends AbstractController
{
    /**
     * @param int $id
     * @param ManagerRegistry $doctrine
     * @return RedirectResponse
     */
    #[Route('/delete/{id}', name: 'app_delete')]
    public function index(int $id, ManagerRegistry $doctrine): RedirectResponse
    {
        //Получение книги по id
        $book = $doctrine
            ->getRepository(Book::class)
            ->findOneBy([
                'id' => $id
            ]);
        //Удаление книги
        $doctrine->getRepository(Book::class)->remove($book, true);
        return $this->redirectToRoute('app_my_bibil');
    }
}