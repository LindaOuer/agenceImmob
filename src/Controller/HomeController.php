<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * Home page
     *
     * @param PropertyRepository $repo
     * @return Response
     * @Route("/",name="home")
     */
    public function index(PropertyRepository $repo): Response
    {
        return $this->render('pages/home.html.twig',[
            'properties' => $repo->findLatest()
        ]);
    }
}