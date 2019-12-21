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
        //Création du formulaire
        $form = $this->createForm(ContactType::class);

        //Affichage de la page contact.html.twig avec le formulaire si la méthode de la requête est GET
        if($request->isMethod('GET'))
        {
            return $this->render('contact.html.twig', [
                'form' => $form->createView()
            ]);

        }
        //Si la méthode est POST je récupère les données du formulaire s'il a été soumis je crée un mail qui sera envoyé au responsable du département
        else if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            
            if($form->isSubmitted()){

                $dataForm = $form->getData();
                
                //Création de l'email
                $email = (new Email())
                ->from($dataForm['mail'])
                ->to($dataForm['departement']->getMailResponsable())
                ->subject('Mail envoyé par ' . $dataForm['firstname'])
                ->text('Mail envoye par ' . $dataForm['mail'] . ' à propos de : ' . $dataForm['message']);
                $sentEmail = $mailer->send($email);

                return $this->render('contact.html.twig', [
                    'form' => $form,
                    'data' => $dataForm
                ]);

            }
        }
    }
}