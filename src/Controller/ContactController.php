<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contact = $form->getData();
            
            $message = (new \Swift_Message('New Contact'))
                        ->setFrom($contact['email'])
                        ->setTo('linda.ouerfelli@gmail.com')
                        ->setBody(
                            $this->renderView('emails/contact.html.twig', compact('contact')),
                            'text/html'
                        );
            
            $mailer->send($message);

            $this->addFlash('message', 'Mail sent');

            return $this->redirectToRoute('home');

        }
        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
