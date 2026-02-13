<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serie', name: 'app_serie')]
final class SerieController extends AbstractController
{
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

    #[Route('/liste/{page}', name: '_liste', requirements: ['page'=> '\d+'], methods: ['GET'])]
    public function liste(SerieRepository $serieRepository,
                          int $page = 1): Response
    {
        //$series = $serieRepository->findAll();

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
/*        $series = $serieRepository->findBy(
            $criterias,
            ['firstAirDate' => 'DESC',
                'dateCreated' => 'DESC'
            ],
            $limit, $offset
        );*/

        $series = $serieRepository->findSerieCustom($offset, $limit,'returning', new \DateTime('1990-01-01'));

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
}
