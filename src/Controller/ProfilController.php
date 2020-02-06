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
use Knp\Component\Pager\PaginatorInterface;

class ProfilController extends AbstractController
{
    /**
     * @Route("/compteaccueil", name="compteaccueil")
     */
    // Section d'accueil sur le compte
    public function compteaccueil(){
        return $this->render('account/compteaccueil.html.twig', [
            'title' => "Bienvenue sur ton compte",
        ]);
    }

    /**
     * @Route("/mycomment", name="mycomment")
     */
    // Section des commentaires de l'utilisateur
    public function mycomment(CommentairesRepository $commentRepo){
        // Récupération des commentaires de l'utilisateur pour l'affichage
        $commentaires = $commentRepo->findBy(["username" => $user = $this->getUser()]);
        return $this->render('account/mycomment.html.twig', [
            'commentaires' => $commentaires 
        ]);
    }

    /**
     * @Route("/gestioncontent", name="gestioncontent")
     */
    // Gestion des textes
    public function gestioncontent(TextesRepository $repo, Request $request){
        // Récupération des textes de l'utilisateur pour affichage
        $texte = $repo->findBy(["username" => $user = $this->getUser()]);

        return $this->render('account/gestioncontent.html.twig', [
            'textes' => $texte,
        ]);
    }

 /**
     * @Route("/gestionvideo", name="gestionvideo")
     */
    // Section gestion de vidéo
 public function gestionvideo(VideosRepository $repo, Request $request){
    // Récupération des vidéos pour l'affichage
    $video = $repo->findBy(["username" => $user = $this->getUser()]);

    return $this->render('account/gestionvideo.html.twig', [
        'videos' => $video
    ]);
}

}
