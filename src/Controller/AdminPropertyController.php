<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController
{
    /**
     * @Route("/admin/property", name="admin_property")
     */
    public function index(PropertyRepository $repo): Response
    {
        $properties = $repo->findAll();
        return $this->render('admin_property/index.html.twig', [
            'properties' => $properties
        ]);
    }

    /**
     * add a new property
     *
     * @param PropertyRepository $repo
     * @param Request $request
     * @return Response
     * @Route("/admin/property/add",name="admin_property_add")
     */
    public function add (PropertyRepository $repo, Request $request): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($property);
            $manager->flush();
            $this->addFlash('success', 'Added successfully');

            return $this->redirectToRoute('admin_property');
        }

        return $this->render('admin_property/add.html.twig', [
            'form' => $form->createView()
        ]);

    }

    /**
     * Update a property
     *
     * @param PropertyRepository $repo
     * @param integer $id
     * @return Response
     * @Route("/admin/property/{id}",name="admin_property_update", methods = "GET|POST")
     */
    public function Update(Property $property, Request $request): Response
    {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->flush();
            $this->addFlash('success', 'Updated successfully');
            return $this->redirectToRoute('admin_property');
        }

        return $this->render('admin_property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }
    
    /**
     * Delete a property
     *
     * @param Property $property
     * @param Request $request
     * @return Response
     * @Route("/admin/property/{id}",name="admin_property_delete", methods="DELETE")
     */
    public function delete(Property $property, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($property);
            $manager->flush();
            $this->addFlash('success', 'Deleted successfully');
        }
        
        return $this->redirectToRoute('admin_property');
    }
}
