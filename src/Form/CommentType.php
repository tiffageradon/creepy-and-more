<?php

namespace App\Form;

use App\Entity\Commentaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use App\Entity\Videos;
use App\Repository\CommentairesRepository;
use App\Repository\TextesRepository;
use App\Repository\UsersRepository;
use App\Entity\Textes;
use App\Repository\VideosRepository;
use App\Entity\Users;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('texte', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Votre commentaire',
                    'class' => 'form-control'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Commentaires::class,
        ]);
    }
}
