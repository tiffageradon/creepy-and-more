<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use App\Entity\Textes;
use App\Repository\TextesRepository;
use App\Repository\UsersRepository;
use App\Entity\Videos;
use App\Repository\VideosRepository;


class ConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('mail', TextType::class, [
            'attr' => [
                'placeholder' => 'Votre adresse mail',
                'class' => 'form-control'
            ]
        ])
        ->add('username', TextType::class, [
           'attr' => [
               'placeholder' => 'Pseudo',
               'class' => 'form-control'
           ]
        ])
        ->add('password', PasswordType::class, [
           'attr' => [
               'placeholder' => 'Mot de passe',
               'class' => 'form-control',
           ]
       ]);

    
    }

    
}
