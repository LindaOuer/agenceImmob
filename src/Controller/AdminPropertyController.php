<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_property")
     */
    public function index(PropertyRepository $repo): Response
    {
        $properties = $repo->findAll();
        return $this->render('admin_property/index.html.twig', [
            'properties' => $properties
        ]);
    }

    /**
     * Update a property
     *
     * @param PropertyRepository $repo
     * @param integer $id
     * @return Response
     * @Route("/admin/edit/{id}",name="admin_property_update")
     */
    public function Update(Property $property): Response
    {
        $form = $this->createForm(PropertyType::class, $property);
        return $this->render('admin_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }
}
