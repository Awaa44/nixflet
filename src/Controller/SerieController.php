<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/serie', name: 'app_serie')]
final class SerieController extends AbstractController
{
    #[Route('/test', name: '_test')]
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
    }

    #[Route('/liste', name: '_liste', methods: ['GET'])]
    public function liste(SerieRepository $serieRepository): Response
    {
        $series = $serieRepository->findAll();

        return $this->render('serie/liste.html.twig', [
            'series' => $series,
        ]);
    }
}
