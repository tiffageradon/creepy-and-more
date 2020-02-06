<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Commentaires;
use App\Repository\CommentairesRepository;
use App\Entity\Textes;
use App\Repository\TextesRepository;
use App\Entity\Users;
use App\Repository\UsersRepository;
use App\Entity\Videos;
use App\Repository\VideosRepository;
use App\Form\TexteType;
use App\Form\VideoType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Knp\Component\Pager\PaginatorInterface;


class NewArticleController extends AbstractController
{
     /**
     * @Route("/newtext", name="newtext")
     */
    // Section nouveau texte
     public function text( Textes $texte = null, Request $request, EntityManagerInterface $manager){

        // Ajout de texte, récupération de l'user et du form
        $texte = new Textes();
        
        $username = $this->getUser();

        $form = $this->createForm(TexteType::class, $texte);

        $form->handleRequest($request);

        // Si le form est entré et valide
        // ajout de l'image en premier suivant les paramêtres puis, les différents ajouts auto
        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();

            if($image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try{
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e){

                }
                $texte->setImage($newFilename);
               
                $texte->setDate(new \DateTime());
                $texte->setSignalement(0);
                $texte->setUsername($username);
                $texte->setDel(0);
                
                $manager->persist($texte);
                $manager->flush();
                
                return $this->redirectToRoute('compteaccueil');
            }
        }

        return $this->render('account/newtext.html.twig', [
            'formTexte' => $form->createView()
        ]);
    }

 /**
     * @Route("/{id}/textedit", name="textedit")
     */
    // Edition de texte
 public function textedit( Textes $texte = null, Request $request, EntityManagerInterface $manager){
    // Récupération user actif, username du texte et l'archivage
    $username = $this->getUser();
    $user = $texte->getUsername();
    $delete = $texte->getDel();

    // Si l'utilisateur est celui qui a mis le texte et que le texte n'est pas archivé, encodage des nouvelles infos et signalement remis à 0
    if($user == $username and $username != NULL and $delete == 0){

        $form = $this->createForm(TexteType::class, $texte);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();

            if($image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                try{
                    $image->move(
                        $this->getParameter('image_directory'),
                        $newFilename
                    );
                } catch (FileException $e){

                }
                $texte->setImage($newFilename);
                
                $texte->setDate(new \DateTime());
                $texte->setSignalement(0);
                $texte->setDel(0);
                
                $manager->persist($texte);
                $manager->flush();
                
                return $this->redirectToRoute('compteaccueil');
            }
        }

        return $this->render('account/edittext.html.twig', [
            'formTexte' => $form->createView()
        ]);
    }else{
        return $this->redirectToRoute('error');
    }
}



    /**
     * @Route("/newvideo", name="newvideo")
     */
    // Nouvelle vidéo
    public function video(Videos $video = null, Request $request, EntityManagerInterface $manager){

    //  Récupération de l'user et du form.
        $video = new Videos();

        $username = $this->getUser();

        $form = $this->createForm(VideoType::class, $video);
        
        $form->handleRequest($request);

        // Si le form est entré et valide, ajout des options auto
        if($form->isSubmitted() && $form->isValid()){

            
            $video->setDate(new \DateTime);
            
            $video->setSignalement(0);
            $video->setDel(0);
            $video->setUsername($username);

            $manager->persist($video);
            $manager->flush();

            return $this->redirectToRoute('compteaccueil');
        }
        return $this->render('account/newvideo.html.twig', [
            'formVideo' => $form->createView()
        ]);
    }


     /**
     * @Route("/{id}/videoedit", name="videoedit")
     */
    // Edition de vidéo
     public function videoedit(Videos $video = null, Request $request, EntityManagerInterface $manager){
        // Récupération du username de la vidéo, de l'user actif et archivage
        $username = $this->getUser();
        $user = $video->getUsername();
        $delete = $video->getDel();

        // Si l'user est celui qui a fait la vidéo et qu'elle n'est pas archivée, modification des infos
        if($user == $username and $username != NULL and $delete == 0){

            $form = $this->createForm(VideoType::class, $video);
            
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){

                if(!$video->getId()){
                    $video->setDate(new \DateTime);
                }
                $video->setSignalement(0);
                $video->setDel(0);
                $video->setUsername($username);

                $manager->persist($video);
                $manager->flush();

                return $this->redirectToRoute('compteaccueil');
            }
            return $this->render('account/editvideo.html.twig', [
                'formVideo' => $form->createView()
            ]);
        }else{
            return $this->redirectToRoute('error');
        }
    }




      /**
     * @Route("/textflag/{id}", name="textflag")
     */
    // Signalement texte
      public function texteflag(Textes $texte, Request $request, EntityManagerInterface $manager){

        // Récupération de l'user et l'username du texte
        $user = $this->getUser();
        $username = $texte->getUsername();

        // Si l'user est différent de celui qui a fait le texte et n'est pas null, signalement mis sur 1
        if($user != $username and $user != NULL){
            $texte->setSignalement(1);

            $manager->persist($texte);
            $manager->flush();


            return $this->redirectToRoute('texttarget', ['id' => $texte->getId()]);
        }else{
            return $this->redirectToRoute('error');
        }
    }

 /**
     * @Route("/videoflag/{id}", name="videoflag")
     */
    // Signalement des vidéos
 public function videoflag(Videos $video, Request $request, EntityManagerInterface $manager){

    // Récupération de l'user et l'username vidéo
    $user = $this->getUser();
    $username = $video->getUsername();

    // Si l'user est différent de celui qui a fait la vidéo et n'est pas null, signalement mis sur 1
    if($user != $username and $user != NULL){
        $video->setSignalement(1);

        $manager->persist($video);
        $manager->flush();

            return $this->redirectToRoute('videotarget', ['id' => $video->getId()]);
    }else{
        return $this->redirectToRoute('error');
    }
}
}