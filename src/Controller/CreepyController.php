<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\Textes;
use App\Entity\Videos;
use App\Form\CommentType;
use App\Entity\Commentaires;
use App\Form\TextsearchType;
use App\Repository\UsersRepository;
use App\Repository\TextesRepository;
use App\Repository\VideosRepository;
use Doctrine\ORM\EntityManagerInterface; 
use App\Repository\CommentairesRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    class CreepyController extends AbstractController
    {

       
        /**
         * @Route("/", name="home")
         */
        // Section accueil
        public function home(TextesRepository $texterepo, VideosRepository $videorepo, PaginatorInterface $paginator, Request $request){
            // Affichage des textes et vidéos
            $textes = $texterepo->findAllHome();
            $videos = $videorepo->findAllHome();
            
            return $this->render('creepy/home.html.twig', 
                [
                    'textes' => $textes, 
                    'videos' => $videos,
                    'title' => "Bienvenue sur Creepy and more"]);
        }

         /**
         * @Route("error", name="error")
         */
        // Erreur 403, acces denied
        public function error(){
            return $this->render('creepy/error.html.twig'); 
        }
        
        /**
         * @Route("/textes", name="textes")
         */
        // Section textes
        public function textes(TextesRepository $repo, Request $request){
            // Barre de recherche pour les textes
            $formBuilder = $this->createFormBuilder(null);
            $formBuilder->setAction($this->generateUrl('searchtextresult'))
            ->add('Recherche', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => "Insérez le nom ou une partie du nom du texte recherché"]
            ])
            ->add('Go!', SubmitType::class, [
                'attr' => ['class' => 'btn btn-default']
            ]);
            
            $form = $formBuilder->getForm();

            $form->handleRequest($request);

            // Pagination des textes
            $pagination = $repo->findByPaginate($request->query->get('page') ? $request->query->get('page') : 1);

            return $this->render('creepy/textes.html.twig', [
                'textes' => $pagination,
                'form' => $form->createView()
            ]);
        }

        /**
         * @Route("/videos", name="videos")
         */
        // Section vidéo
        public function videos(VideosRepository $repo, Request $request){
            // Barre de recherche des vidéos
            $formBuilder = $this->createFormBuilder(null);
            $formBuilder->setAction($this->generateUrl('searchvideoresult'))
            ->add('Recherche', TextType::class, [
                'attr' => ['class' => 'form-control', 'placeholder' => "Insérez le nom ou une partie du nom de la vidéo recherchée"]
            ])
            ->add('Go!', SubmitType::class, [
                'attr' => ['class' => 'btn btn-default']
            ]);
            
            $form = $formBuilder->getForm();

            $form->handleRequest($request);
            
            // Pagination des vidéos
            $pagination = $repo->findByPaginate($request->query->get('page') ? $request->query->get('page') : 1);
            return $this->render('creepy/videos.html.twig', [
                'videos' => $pagination,
                'form' => $form->createView()
            ]);
        }

        /**
         * @Route("/searchtextresult", name="searchtextresult")
         */
        // Résultat de recherche des textes
        public function textsearch(Request $request, TextesRepository $repo) {
            // Affichage des résultats
            $Recherche = $request->request->get('form')['Recherche'];

            if($Recherche) { 
                $texte = $repo->searchTextLike($Recherche);
            }
            return $this->render('search/textresult.html.twig', [
                'textes' => $texte,
                'query' => $Recherche
            ]);
        }

        /**
         * @Route("/searchvideoresult", name="searchvideoresult")
         */
        // Résultat de recherche des vidéos
        public function videosearch(Request $request, VideosRepository $repo) {
            
            // Affichage des résultats de recherche
            $Recherche = $request->request->get('form')['Recherche'];

            if($Recherche) { 
                $video = $repo->searchVideoLike($Recherche);
            }

            return $this->render('search/videoresult.html.twig', [
                'videos' => $video,
                'query' => $Recherche
            ]);
            
        }
        
        /**
         * @Route("/contenu/{username}", name="contenu")
         */
        // Affichage du contenu de l'utilisateur
        public function contenu($username, UsersRepository $userRepo, TextesRepository $texterepo, VideosRepository $videorepo){

            // Récupération de l'user ciblé ainsi que ses textes et vidéos pour l'affochage
            $user = $userRepo->findOneBy(['username' => $username]);
            $textes = $texterepo->findBy(["username" => $user->getId()]);
            $videos = $videorepo->findBy(["username" => $user->getId()]);

            return $this->render('creepy/contenu.html.twig', [
                'user' => $user,
                'textes' => $textes, 
                'videos' => $videos,
                'title' => "Bienvenue sur le contenu de {$username}"
                
            ]);
        }
        
        /**
         * @Route("/texttarget/{id}", name="texttarget")
         */
        // Texte ciblé par le lien
        public function texttarget( $id, TextesRepository $texteRepo, CommentairesRepository $commentaireRepo, Request $request, EntityManagerInterface $manager){

            // Récupération du texte, ses commentaires et le form de commentaire ainsi que l'user courant pour les commentaires
            $texte = $texteRepo->find($id);
            $commentaire = new Commentaires();
            $form = $this->createForm(CommentType::class, $commentaire);
            $username = $this->getUser();
            
            $form->handleRequest($request);

            // Si un commentaire est entré, indique les différentes options automatiques à mettre 
            if($form->isSubmitted() && $form->isValid()){
                $commentaire->setDate(new \DateTime);
                $commentaire->setSignalement(0);
                $commentaire->setUsername($username);
                $commentaire->setIdtexte($texte);
                $commentaire->setDel(0);

                $manager->persist($commentaire);
                $manager->flush();

                return $this->redirectToRoute('texttarget', ['id' => $texte->getId()]);
            }
            return $this->render('creepy/texttarget.html.twig', [
                'texte' => $texte,
                'commentaire' => $commentaire,
                'commentForm' => $form->createView()
            ]);
        }

        /**
         * @Route("/commenttext/{id}", name="commenttext")
         */
        // Modification de commentaire
        public function commenttextedit(Commentaires $commentaire, CommentairesRepository $commentaireRepo, Request $request, EntityManagerInterface $manager){

            // Récupération de l'user, l'username du commentaire et si le commentaire est archivé
            $user = $this->getUser();
            $username = $commentaire->getUsername();
            $delete = $commentaire->getDel();

            // Si l'user est celui qui a posté le commentaire et que le commentaire n'est pas archivé, création du form pour ensuite modifier le commentaire sinon erreur
            if($user == $username and $user != NULL and $delete == 0){

                $form = $this->createForm(CommentType::class, $commentaire);
                $username = $this->getUser();
                
                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()){
                 
                    $commentaire->setSignalement(0);

                    $manager->persist($commentaire);
                    $manager->flush();

                    return $this->redirectToRoute('compteaccueil');
                }
                return $this->render('account/comment.html.twig', [
                    'commentForm' => $form->createView()
                ]);
            }else{
                return $this->redirectToRoute('error');
            }
        }

        /**
         * @Route("/commentflag/{id}", name="commentflag")
         */
        // Signalement de commentaires
        public function commentflag(Commentaires $commentaire, CommentairesRepository $commentaireRepo, Request $request, EntityManagerInterface $manager){

            // Récupération de l'user et username du commentaire
            $user = $this->getUser();
            $username = $commentaire->getUsername();

            // Si user est différent de username, et que l'user est pas null, signalement mis à 1 sinon, erreur
            if($user != $username and $user != NULL){
                $commentaire->setSignalement(1);

                $manager->persist($commentaire);
                $manager->flush();

                // Récupération des id texte et vidéo du commentaire
                $texte = $commentaire->getIdtexte();
                $video = $commentaire->getIdvideo();
                
                // Redirection vers le texte ou la vidéo concernée suivant l'ID texte ou vidéo
                if($texte != NULL){
                    return $this->redirectToRoute('texttarget', ['id' => $texte->getId()]);
                } else if($video != NULL){
                    return $this->redirectToRoute('videotarget', ['id' => $video->getId()]);
                }

            }else{
                return $this->redirectToRoute('error');
            }
        }

        
        /**
         * @Route("/videotarget/{id}", name="videotarget")
         */
        // Vidéo cible du lien
        public function videotarget(VideosRepository $repo, $id, CommentairesRepository $commentaireRepo, Request $request, EntityManagerInterface $manager){
        //    Récupération de la vidéo, du username, et form pour les commentaires
            $video = $repo->find($id);
            $commentaire = new Commentaires();
            $username = $this->getUser();
            $form = $this->createForm(CommentType::class, $commentaire);
            
            $form->handleRequest($request);

            // Si un commentaire est entré, indique les différentes options automatiques à mettre 
            if($form->isSubmitted() && $form->isValid()){
                $commentaire->setDate(new \DateTime);
                $commentaire->setSignalement(0);
                $commentaire->setUsername($username);
                $commentaire->setIdvideo($video);
                $commentaire->setDel(0);

                $manager->persist($commentaire);
                $manager->flush();

                return $this->redirectToRoute('videotarget', ['id' => $video->getId()]);
            }
            return $this->render('creepy/videotarget.html.twig', [
                'video' => $video,
                'commentaire' => $commentaire,
                'commentForm' => $form->createView()
            ]);
        }

        /**
         * @Route("/cgu", name="cgu")
         */
        // Lien vers les conditions générales
        public function cgu(UsersRepository $repo, Request $request, EntityManagerInterface $manager){

            // Récupération des users pour afficher les admin dans les CGU
            $users = $repo->findAll();

            return $this->render('creepy/cgu.html.twig', [
                'title' => "CONDITIONS GÉNÉRALES D’UTILISATION",
                'user' => $users,
            ]);
        }

    }
