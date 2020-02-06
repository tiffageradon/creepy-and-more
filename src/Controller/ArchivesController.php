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

class ArchivesController extends AbstractController
{
     /**
     * @Route("/archivestextes", name="archivestextes")
     */
    // Section textes archivés
     public function archivestextes(TextesRepository $repo, Request $request)
     {
        //  Barre de recherche pour les textes archivés
        $formBuilder = $this->createFormBuilder(null);
        $formBuilder->setAction($this->generateUrl('searcharchivetextresult'))
        ->add('Recherche', TextType::class, [
            'attr' => ['class' => 'form-control', 'placeholder' => "Insérez le nom ou une partie du nom du texte recherché"]
        ])
        ->add('Go!', SubmitType::class, [
            'attr' => ['class' => 'btn btn-default']
        ]);
        
        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        // Affichage des textes archivés par pagination
        $pagination = $repo->findByArchive($request->query->get('page') ? $request->query->get('page') : 1);
        return $this->render('admin/archivestextes.html.twig', [
            'controller_name' => 'Creepy and more admin',
            'textes' => $pagination,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/searcharchivetextresult", name="searcharchivetextresult")
     */
    // Résultat de la recherche de textes
     public function textsearch(Request $request, TextesRepository $repo) {
        // Affichage des textes 
        $Recherche = $request->request->get('form')['Recherche'];

        if($Recherche) { 
            $texte = $repo->searchTextArchives($Recherche);
        }
        return $this->render('admin/archivetextresult.html.twig', [
            'textes' => $texte,
            'query' => $Recherche
        ]);
    }

    /**
     * @Route("/archivesvideos", name="archivesvideos")
     */
    // Section des vidéos archivées
    public function archivesvideos(VideosRepository $repo, Request $request)
    {
        // Barre de recherche des vidéos
        $formBuilder = $this->createFormBuilder(null);
        $formBuilder->setAction($this->generateUrl('searcharchivevideoresult'))
        ->add('Recherche', TextType::class, [
            'attr' => ['class' => 'form-control',  'placeholder' => "Insérez le nom ou une partie du nom de la vidéo recherchée"]
        ])
        ->add('Go!', SubmitType::class, [
            'attr' => ['class' => 'btn btn-default']
        ]);
        
        $form = $formBuilder->getForm();

        $form->handleRequest($request);

        // Pagination des vidéos archivées
        $pagination = $repo->findByArchive($request->query->get('page') ? $request->query->get('page') : 1);
        return $this->render('admin/archivesvideos.html.twig', [
            'videos' => $pagination,
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route("/searcharchivevideoresult", name="searcharchivevideoresult")
     */
    // Recherche des archives vidéos
     public function videosearch(Request $request, VideosRepository $repo) {
    // Affichage des vidéos archivées recherchées
        $Recherche = $request->request->get('form')['Recherche'];

        if($Recherche) { 
            $video = $repo->searchVideoArchives($Recherche);
        }

        return $this->render('admin/archivevideoresult.html.twig', [
            'videos' => $video,
            'query' => $Recherche
        ]);
        
    }

    /**
     * @Route("/archivescomment", name="archivescomment")
     */
    // Section commentaires archivés
    public function archivescomment(CommentairesRepository $repo, Request $request)
    {
        // Pagination des commentaires archivés
        $pagination = $repo->findByArchive($request->query->get('page') ? $request->query->get('page') : 1);
        return $this->render('admin/archivescomment.html.twig', [
            'commentaire' => $pagination
        ]);
    }
}
