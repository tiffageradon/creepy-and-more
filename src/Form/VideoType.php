<?php

namespace App\Form;

use App\Entity\Videos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use App\Repository\TextesRepository;
use App\Repository\UsersRepository;
use App\Entity\Textes;
use App\Repository\VideosRepository;
use App\Entity\Users;

class VideoType extends AbstractType
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
        ->add('description', TextType::class, [
           'attr' => [
               'placeholder' => 'Description',
               'class' => 'form-control',
               'id' => 'input_Description'
           ]
       ])
        ->add('url', TextType::class, [
           'attr' => [
               'placeholder' => 'Url de la vidéo',
               'class' => 'form-control'
           ]
       ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Videos::class,
        ]);
    }
}
