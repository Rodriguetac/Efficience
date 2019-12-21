<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Departement;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);

        if($request->isMethod('GET'))
        {
            return $this->render('contact.html.twig', [
                'form' => $form->createView()
            ]);
        }else if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            
            if($form->isSubmitted()){

                $dataForm = $form->getData();

                $email = (new Email())
                ->from($dataForm['mail'])
                ->to($dataForm['departement']->getMailResponsable())
                ->subject('Mail addressé au responsable du departement' . $dataForm['departement']->getNom())
                ->text($dataForm['message']);
                $sentEmail = $mailer->send($email);

                return $this->redirectRoute('/contact');

            }
        }
    }
}