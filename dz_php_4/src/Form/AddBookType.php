<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\Image;

class AddBookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Название произведения',
                'attr' => [
                    'placeholder' => 'Введите название сюда',
                    'class' => 'form-control',
                ]
            ])
            ->add('author', TextType::class, [
                'label' => 'Автор',
                'attr' => [
                    'placeholder' => 'Введите имя сюда',
                    'class' => 'form-control',
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'Изображение',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control',
                    'accept' => "image/*",

                ],
                'constraints' => [
                    new Image()
                ]
            ])
            ->add('file', FileType::class, [
                'label' => 'Книга',
                'empty_data' => null,
                'attr' => [
                    'class' => 'form-control',

                ]
            ])
            ->add('dateRead', DateTimeType::class, [
                'label' => 'Дата прочтения',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Загрузить',
                'attr' => [
                    'class' => 'btn btn-primary mb-2 tex col text-center',
                ]
            ]);
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
