<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serie', name: 'app_serie')]
final class SerieController extends AbstractController
{
    #[Route('/liste/find_by/{page}', name: '_liste_find_by', requirements: ['page'=> '\d+'], methods: ['GET'])]
    public function listeFindBy(SerieRepository $serieRepository, int $page = 1): Response
    {
        //appel aux paramètres définis dans config/services.yaml
        $limit = $this->getParameter('nb_limit_series');
        //restriction à page sup à 1
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $criterias = [
            'status' => 'returning'];

        $nbTotal = $serieRepository->count($criterias);
        $nbPagesMax = ceil($nbTotal / $limit);

        if ($page > $nbPagesMax) {
            throw $this->createNotFoundException("La page $page n'existe pas.");
        }

        //méthode héritée qui utilise un tableau de critères binaires
        $series = $serieRepository->findBy(
            $criterias,
            ['firstAirDate' => 'DESC',
                'dateCreated' => 'DESC'
            ],
            $limit, $offset
        );

        return $this->render('serie/liste.html.twig', [
            'series' => $series,
            'page' => $page,
            'nb_pages_max' => $nbPagesMax,
        ]);
    }

    #[Route('/liste/find_custom/{page}', name: '_liste_find_custom', requirements: ['page'=> '\d+'], methods: ['GET'])]
    public function listeFindCustom(SerieRepository $serieRepository, int $page = 1): Response
    {
        //appel aux paramètres définis dans config/services.yaml
        $limit = $this->getParameter('nb_limit_series');

        //restriction à page sup à 1
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        //ici dans liste list(q2, q)
        list($nbTotal, $series) = $serieRepository->findSerieCustom($offset, $limit,'returning',
                                                                    new \DateTime('1990-01-01'), 8);

        $nbPagesMax = ceil($nbTotal / $limit);

        if ($page > $nbPagesMax) {
            throw $this->createNotFoundException("La page $page n'existe pas.");
        }

        return $this->render('serie/liste.html.twig', [
            'series' => $series,
            'page' => $page,
            'nb_pages_max' => $nbPagesMax,
        ]);
    }

    #[Route('/liste/{page}', name: '_liste', requirements: ['page'=> '\d+'], methods: ['GET'])]
    public function liste(SerieRepository $serieRepository, int $page = 1): Response {

        //appel aux paramètres définis dans config/services.yaml
        $limit = $this->getParameter('nb_limit_series');

        //restriction à page sup à 1
        $page = max($page, 1);
        $offset = ($page - 1) * $limit;

        $nbTotal = $serieRepository->count([]);
        $nbPagesMax = ceil($nbTotal / $limit);

        if ($page > $nbPagesMax) {
            throw $this->createNotFoundException("La page $page n'existe pas.");
        }

        $series = $serieRepository->findBy([], ['popularity' => 'DESC',], $limit, $offset);

        return $this->render('serie/liste.html.twig', [
            'series' => $series,
            'page' => $page,
            'nb_pages_max' => $nbPagesMax,
        ]);
    }

    #[Route('/detail/{id}', name: '_detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(Serie $serie): Response {

        return $this->render('serie/detail.html.twig', [
            'serie' => $serie,
        ]);
    }

    //par défaut, une ROUTE est disponible en GET et POST
    #[Route('/create', name: '_create')]
    public function create(Request $request, EntityManagerInterface $em) : Response {

        //instancier un objet Serie vide puis on le passe au formulaire
        $serie = new Serie();

        //on créé le formulaire en précisant le nom de la classe concernée et on passe l'objet série
        $serieForm = $this->createForm(SerieType::class, $serie);

        //permet de savoir si oui ou non une soumission a été faite
        $serieForm->handleRequest($request);

        //CAS NOMINAL
        //si une soumission a été faite alors
        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $serie->setDateCreated(new \DateTime());
            //enregistrement en BDD avec EntityManagerInterface $em
            $em->persist($serie);
            $em->flush();

            //message de confirmation d'ajout avec addFlash (il faut prévoir un espace dans Base
            // pour afficher les messages Flash
            $this->addFlash('success', 'Une nouvelle série a été enregistrée');
            //redirection vers la liste des série
            return $this->redirectToRoute('app_serie_liste');
        }


        return $this->render('serie/edit.html.twig', [
            'serie_form' => $serieForm,
        ]);
    }



    /*#[Route('/test', name: '_test')]
public function test(EntityManagerInterface $em): Response
{
    //création d'un objet serie
    $serie = new Serie();
    $serie->setName('Derrick')
        ->setOverview("inspecteur de choc")
        ->setStatus('Ended')
        ->setGenres('serie policière allemande')
        ->setFirstAirDate(new \DateTime('1974-10-20'))
        ->setLastAirDate(new \DateTime('1998-10-16'))
        ->setDateCreated(new \DateTime());

    $em->persist($serie);
    $em->flush();

    return new Response('Une nouvelle série a été créée');
}*/
}
