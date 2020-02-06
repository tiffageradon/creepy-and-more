<?php

namespace App\Form;

use App\Entity\Textes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use App\Repository\TextesRepository;
use App\Repository\UsersRepository;
use App\Entity\Videos;
use App\Repository\VideosRepository;
use App\Entity\Users;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;

class TexteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre', TextType::class, [
            'attr' => [
                'placeholder' => 'Titre',
                'class' => 'form-control',
                'id' => 'input_title'
            ]
        ])
        ->add('texte', TextareaType::class, [
           'attr' => [
               'placeholder' => 'Texte...',
               'class' => 'form-control',
               'rows' => '6',
               'id' => 'input_message'
           ]
        ])
        ->add('description', TextType::class, [
           'attr' => [
               'placeholder' => 'Description',
               'class' => 'form-control',
               'id' => 'input_Description'
           ]
       ])
        ->add('image', FileType::class, [
           
               'mapped' => false,
               'required' => true,

               'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                ])
        ],
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Textes::class,
        ]);
    }
}
