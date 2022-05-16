<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Загрузка тестовых данных в бд
        $user_danila = new User();
        $user_danila->setUsername('Danila');
        $user_danila->setPassword(password_hash('123456', PASSWORD_DEFAULT));
        $user_danila->setRoles(['ROLE_ADMIN']);
        $manager->persist($user_danila);

        $book_danila = new Book();
        $book_danila->setPicture("/uploads/true_man.jpg");
        $book_danila->setFile("/uploads/true_man.pdf");
        $book_danila->setName("Повесть о настоящем человек");
        $book_danila->setAuthor("Борис Полевой");
        $book_danila->setDateRead(new \DateTime('now'));
        $book_danila->setUser($user_danila);
        $manager->persist($book_danila);

        $user_sasha = new User();
        $user_sasha->setUsername('Sasha');
        $user_sasha->setPassword(password_hash('123456', PASSWORD_DEFAULT));
        $user_sasha->setRoles(['ROLE_USER']);
        $manager->persist($user_sasha);

        $book_sasha = new Book();
        $book_sasha->setPicture("/uploads/autostop_galaktika.jpg");
        $book_sasha->setFile("/uploads/autostop_galaktika.pdf");
        $book_sasha->setName("Автостопом по галактике");
        $book_sasha->setAuthor("Дуглас Адамс");
        $book_sasha->setDateRead(new \DateTime('now'));
        $book_sasha->setUser($user_sasha);
        $manager->persist($book_sasha);

        $manager->flush();
    }
}
