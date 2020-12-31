<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator, \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            //generate activation token
            $user->setActivationToken(md5(uniqid()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            $message = (new \Swift_Message('Account Activation'))
                        ->setFrom('linda.ouerfelli@gmail.com')
                        ->setTo($user->getEmail())
                        ->setBody(
                            $this->renderView('emails/activation.html.twig', ['token'=>$user->getActivationToken()]),
                            'text/html'
                        );
            
            $mailer->send($message);

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * Verify activation token
     *
     * @param [type] $token
     * @param UserRepository $repo
     * @return Response
     * @Route("/activation/{token}",name="register_token")
     */
    public function activation ($token, UserRepository $repo) : Response
    {
        $user = $repo->findOneBy(['ActivationToken' => $token]);

        if (!$user) {
            throw $this->createNotFoundException('User Not Found!!!');
            
        }

        $user->setActivationToken(Null);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($user);
        $manager->flush();

        $this->addFlash('message', 'Account activated');

        return $this->redirectToRoute('home');
    }
}
