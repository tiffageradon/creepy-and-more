<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use App\Security\Model\ChangePasswordModel;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Knp\Component\Pager\PaginatorInterface;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('oldPassword', PasswordType::class, [
            'mapped' => false,
            'attr' => ['class' => 'form-control'],
            'label' => 'Mot de passe actuel',
        ])
        ->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'Les champs ne correspondent pas',
            'options' => ['attr' => ['class' => 'form-control']],
            'required' => true,
            'first_options'  => ['label' => 'Mot de passe'],
            'second_options' => ['label' => 'Répétez le mot de passe'],
        ])
        ->add('submit', SubmitType::class, [
            'attr' => ['class' => "btn btn-primary btn-right"]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ChangePasswordModel::class,
        ]);
    }
}
