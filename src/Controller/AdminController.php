<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use App\Entity\Textes;
use App\Repository\TextesRepository;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Entity\Videos;
use App\Repository\VideosRepository;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Form\RoleType;
use Knp\Component\Pager\PaginatorInterface;

    class AdminController extends AbstractController
    {
        /**
         * @Route("/admin", name="admin")
         */

        //  Page d'index de l'admin
        public function index(TextesRepository $texteRepo, VideosRepository $videoRepo, UsersRepository $userRepo, CommentairesRepository $commentaireRepo)
        {
            // Ce qui est à afficher dans les entités (toutes donc)
            $textes = $texteRepo->findAll();
            $videos = $videoRepo->findAll();
            $users = $userRepo->findAll();
            $commentaire = $commentaireRepo->findAll();
            return $this->render('admin/index.html.twig', [
                'controller_name' => 'Creepy and more admin',
                'textes' => $textes,
                'videos' => $videos,
                'users' => $users,
                'commentaire' => $commentaire
            ]);
        }

        /**
         * @Route("/admintextes", name="admintextes")
         */
        // Partie textes de l'admin, pour la gestion
        public function admintextes(TextesRepository $repo, Request $request)
        {
            // Pagination des textes pour affichage
            $pagination = $repo->findByAdminSign($request->query->get('page') ? $request->query->get('page') : 1);
            return $this->render('admin/admintextes.html.twig', [
                'textes' => $pagination
            ]);
        }

        /**
         * @Route("/adminvideos", name="adminvideos")
         */
        // Partie vidéos de l'admin pour la gestion
        public function adminvideos(VideosRepository $repo, Request $request)
        {
            // Affichage par pagination
            $pagination = $repo->findByAdminSign($request->query->get('page') ? $request->query->get('page') : 1);
            return $this->render('admin/adminvideos.html.twig', [
                'videos' => $pagination
            ]);
        }

        /**
         * @Route("/adminusers", name="adminusers")
         */
        // Partie users de l'admin pour la gestion
        public function adminusers(UsersRepository $repo, Request $request, EntityManagerInterface $manager)
        {
            // Formulaire pour afficher la barre de recherche
            $formBuilder = $this->createFormBuilder(null);
            $formBuilder->setAction($this->generateUrl('searchuserresult'))
                ->add('Recherche', TextType::class, [
                    'attr' => ['class' => 'form-control', 'placeholder' => "Insérez le nom ou une partie du nom de la personne recherchée"]
                ])
                ->add('GO!', SubmitType::class, [
                    'attr' => ['class' => 'btn btn-default']
                ]);
            
            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            // Affichage des utilisateurs 
            $pagination = $repo->findByPaginate($request->query->get('page') ? $request->query->get('page') : 1);

            return $this->render('admin/adminusers.html.twig', [
                'users' => $pagination,
                'form' => $form->createView()
            ]);
        }

         /**
         * @Route("/searchuserresult", name="searchuserresult")
         */
        // Résultat de recherche des utilisateurs
        public function usersearch(Request $request, UsersRepository $repo) {
            // Affichage des utilisateurs
            $Recherche = $request->request->get('form')['Recherche'];

            if($Recherche) { 
                $user = $repo->searchUserLike($Recherche);
            }
            return $this->render('admin/searchuserresult.html.twig', [
                'user' => $user,
                'query' => $Recherche
            ]);
        }

         /**
         * @Route("/admincomment", name="admincomment")
         */
        // Partie commentaires de l'admin pour la gestion
        public function admincomment(CommentairesRepository $repo, Request $request, EntityManagerInterface $manager)
        {
            // Affichage des commentaires par pagination
            $pagination = $repo->findByAdminSign($request->query->get('page') ? $request->query->get('page') : 1);


            return $this->render('admin/admincomment.html.twig', [
                'commentaire' => $pagination
            ]);
        }

         /**
         * @Route("/commentadmin/{id}", name="commentadmin")
         */
        // Validation du commentaire
        public function commentadmin(Commentaires $commentaire, CommentairesRepository $commentaireRepo, Request $request, EntityManagerInterface $manager){
            // récupération de l'user et son role
            $admin = $this->getUser();
            $role = $admin->getRoles();
            
            // Si admin, validation du commentaire et remise à 0 du signalement sinon, redirection vers erreur
            if($role == ["ROLE_ADMIN"]){

            $commentaire->setSignalement(0);

            $manager->persist($commentaire);
            $manager->flush();
            return $this->redirectToRoute('admincomment');
            }else{
                return $this->redirectToRoute('error');
        }
    }
        /**
         * @Route("/textflagadmin/{id}", name="textflagadmin")
         */
        // Validation de texte
        public function texteflag(Textes $texte, Request $request, EntityManagerInterface $manager){
            // Récupération de l'user et son role
            $admin = $this->getUser();
            $role = $admin->getRoles();
            
            // Si role admin, validation du texte et remise à 0 du signalement sinon, redirection vers erreur
            if($role == ["ROLE_ADMIN"]){
            $texte->setSignalement(0);

            $manager->persist($texte);
            $manager->flush();
            return $this->redirectToRoute('admintextes');
            }else{
                return $this->redirectToRoute('error');
            }
    }

     /**
         * @Route("/videoflagadmin/{id}", name="videoflagadmin")
         */
        // Validation de vidéos
        public function videoflag(Videos $video, Request $request, EntityManagerInterface $manager){
        //    Récupération de l'user et son rôle
            $admin = $this->getUser();
            $role = $admin->getRoles();
        
            // Si admin, validation de la vidéo et remise à 0 du signalement sinon, redirection vers erreur
            if($role == ["ROLE_ADMIN"]){

            $video->setSignalement(0);

            $manager->persist($video);
            $manager->flush();
            return $this->redirectToRoute('adminvideos');
            }else{
                return $this->redirectToRoute('error');
            }

    }

    /**
         * @Route("/commentdeladmin/{id}", name="commentdeladmin")
         */
        // Suppression de commentaire (archivage)
        public function commentdeladmin(Commentaires $commentaire, CommentairesRepository $commentaireRepo, Request $request, EntityManagerInterface $manager){
            
            // Récupération de l'user, son rôle et l'username sur le commentaire
            $user = $this->getUser();
            $role = $user->getRoles();
            $username = $commentaire->getUsername();

            // Si admin ou l'user qui a posté le commentaire, signalement retombe à 0 et del (archivage) est à 1 sinon, erreur
            if($role == ["ROLE_ADMIN"] or $user == $username and $user != NULL){
            $commentaire->setSignalement(0);
            $commentaire->setDel(1);

            $manager->persist($commentaire);
            $manager->flush();

            $this->addFlash(
                'com_deleted',
                'Commentaire supprimé'
            );
           
            if($role == ["ROLE_ADMIN"]){
            return $this->redirectToRoute('admincomment');
            }else {
                return $this->redirectToRoute('mycomment');
            }
        }else{
            return $this->redirectToRoute('error');
        }
    }
        /**
         * @Route("/textdeladmin/{id}", name="textdeladmin")
         */
        // Suppression du texte (archivage)
        public function textedel(Textes $texte, Request $request, EntityManagerInterface $manager){

            // Récupération de l'user, son rôle et l'username sur le texte
            $user = $this->getUser();
            $role = $user->getRoles();
            $username = $texte->getUsername();
            
            // Si admin ou utilisateur qui a posté le texte, archivage sur 1 et signalement remis à 0 sinon, erreur
            if($role == ["ROLE_ADMIN"] or $user == $username and $user != NULL){
            $texte->setSignalement(0);
            $texte->setDel(1);

            $manager->persist($texte);
            $manager->flush();

            $this->addFlash(
                'com_deleted',
                'Texte supprimé'
            );

            if($role == ["ROLE_ADMIN"]){
            return $this->redirectToRoute('admintextes');
            }else {
                return $this->redirectToRoute('gestioncontent');
            }
            }else{
                return $this->redirectToRoute('error');
            }
    }

        /**
        * @Route("/videodeladmin/{id}", name="videodeladmin")
        */
        // Suppression de vidéo
        public function videodel(Videos $video, Request $request, EntityManagerInterface $manager){

            // Récupération de l'user, son rôle et l'username sur la vidéo
            $user = $this->getUser();
            $role = $user->getRoles();
            $username = $video->getUsername();
            
            // Si admin ou utilisateur qui a posté la vidéo, archivage sur 1 et signalement remis à 0 sinon, erreur
            if($role == ["ROLE_ADMIN"] or $user == $username and $user != NULL){

            $video->setSignalement(0);
            $video->setDel(1);

            $manager->persist($video);
            $manager->flush();

            $this->addFlash(
                'com_deleted',
                'Vidéo supprimée'
            );
            $user = $this->getUser();
            $role = $user->getRoles();

            if($role == ["ROLE_ADMIN"]){
                return $this->redirectToRoute('adminvideos');
                }else {
                    return $this->redirectToRoute('gestioncontent');
                }
                }else{
                    return $this->redirectToRoute('error');
                }
    }

    /**
     * @Route("/usermembre/{id}", name="usermembre")
     */
    // Fonction pour mettre role user
    public function usermembre(Users $user, Request $request, EntityManagerInterface $manager){

        // Récupération de l'user et son role
        $admin = $this->getUser();
        $role = $admin->getRoles();

        // Si rôle admin, met un utilisateur en user simple sinon, erreur
        if($role == ["ROLE_ADMIN"]){

        $user->setRoles(['ROLE_USER']);

        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('adminusers');
    }else{
        return $this->redirectToRoute('error');
    }

    }

    /**
     * @Route("/userbanni/{id}", name="userbanni")
     */
    // Fonction pour mettre role banni
    public function userbanni(Users $user, Request $request, EntityManagerInterface $manager){

        // Récupération de l'user et son role
        $admin = $this->getUser();
        $role = $admin->getRoles();

        // Si admin, l'utilisateur ciblé sera banni sinon, erreur
        if($role == ["ROLE_ADMIN"]){

        $user->setRoles(['ROLE_BANNI']);

        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('adminusers');
    }else{
        return $this->redirectToRoute('error');
    }
    }

    /**
     * @Route("/useradmin/{id}", name="useradmin")
     */
    // Fonction pour mettre en admin un user
    public function useradmin(Users $user, Request $request, EntityManagerInterface $manager){

        // Récupération de l'user et son rôle
        $admin = $this->getUser();
        $role = $admin->getRoles();

        // Si role admin, met un user en admin sinon, erreur
        if($role == ["ROLE_ADMIN"]){
        $user->setRoles(['ROLE_ADMIN']);

        $manager->persist($user);
        $manager->flush();
        return $this->redirectToRoute('adminusers');
    }else{
        return $this->redirectToRoute('error');
    }
    }

    }

