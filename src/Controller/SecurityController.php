<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Textes;
use App\Entity\Videos;
use App\Form\ConnexionType;
use App\Entity\Commentaires;
use App\Form\ChangePasswordType;
use App\Repository\UsersRepository;
use App\Repository\TextesRepository;
use App\Repository\VideosRepository;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface; 
use App\Repository\CommentairesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Security\Model\ChangePasswordModel;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Knp\Component\Pager\PaginatorInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/registration", name="registration")
     */
    // Inscription sur le site
    public function registration(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder){
        // Ajout de l'user, récupération du form
        $user = new Users();

        $form = $this->createForm(ConnexionType::class, $user);
        $form->handleRequest($request);
        // Si le form est valide, ajout en DB avec hash du mdp et donne un token
        if($form->isSubmitted() && $form->isValid()){
         $hash = $encoder->encodepassword($user, $user->getPassword());
         $user->setPassword($hash);
         $user->setRoles(['ROLE_USER']);

         $manager->persist($user);
         $manager->flush();

         $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
         $this->get('security.token_storage')->setToken($token);
         $this->get('session')->set('_security_main', serialize($token));

         return $this->redirectToRoute('connexion');
     }                    
     return $this->render('creepy/registration.html.twig', [
        'form' => $form->createView()
    ]);
     
 }
     /**
     * @Route("/connexion", name="connexion", methods={"GET", "POST"})
     */
    // Connexion sur le site
     public function connexion(AuthenticationUtils $authenticationUtils) {
    
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('creepy/connexion.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/deconnexion", name="deconnexion")
     */
    // Déconnexion
    public function logout() {
    }

    /**
     * @Route("/password", name="password")
     */
    // Changement de MDP
    public function password(Request $request, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager){
        

        /**
         * @var User $user
         */
        $user = $this->getUser();

        $changePasswordInfos = new ChangePasswordModel();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordInfos);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() && !$passwordEncoder->isPasswordValid($user, $changePasswordInfos->getOldPassword())) {
            $user->setPassword($passwordEncoder->encodePassword($user, $changePasswordInfos->getNewPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('compteaccueil');
        } elseif ($form->isSubmitted() && $passwordEncoder->isPasswordValid($user, $changePasswordInfos->getOldPassword())) {
            $formError = new FormError("l'ancien mot de passe est incorrect");
            $form->get('oldPassword')->addError($formError);
        }

        return $this->render('account/password.html.twig', [        
            'form' => $form->createView()
        ]);


    }

}