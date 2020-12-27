<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    /**
     * Undocumented function
     *
     * @return Response
     * @Route("/properties", name="property_index")
     */
    public function index(): Response
    {
        /* $property = new Property();
        $property->setAddress('01 boulvard Habib Bourguiba')
                ->setBedrooms(3)
                ->setCity('Tunis')
                ->setDescription('Lorem ipsum dolor sit amet consectetur, adipisicing elit. Hic nisi dolor labore possimus omnis dolore praesentium ullam cum soluta magnam nemo, voluptatibus qui repellat quod libero obcaecati ducimus, doloribus at.')
                ->setFloor(2)
                ->setHeat(1)
                ->setPostalCode(2000)
                ->setPrice(200000)
                ->setRooms(3)
                ->setSurface(100)
                ->setTitle('Property 1');

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($property);
        $manager->flush(); */


        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

    /**
     * show property
     *
     * @return Response
     * @Route("/properties/{slug}-{id}",name="property_show", requirements={"slug": "[a-z0-9\-]*"})
     */
    public function show($id, $slug, PropertyRepository $repo): Response
    {

        return $this->render('property/show.html.twig', [
            'property' => $repo->find($id),
            'current_menu' => 'properties'
        ]);
    }
}
