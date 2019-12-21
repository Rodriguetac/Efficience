<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Departement;
use App\Entity\Mail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

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

                //Obtention des données du formulaire
                $dataForm = $form->getData();

                //Création de l'email
                $email = (new Email())
                ->from($dataForm['mail'])
                ->to($dataForm['departement']->getMailResponsable())
                ->subject('Mail envoyé par ' . $dataForm['firstname'])
                ->text('Mail envoyé par ' . $dataForm['mail'] . ' à propos de : ' . $dataForm['message']);
                
                //Envoi de l'email
                $sentEmail = $mailer->send($email);
                

                $manager = $this->getDoctrine()->getManager();

                //Creation d'un mail qu'on enverra dans la bdd
                $mail = (new Mail())
                ->setNom($dataForm['lastname'])
                ->setPrenom($dataForm['firstname'])
                ->setMail($dataForm['mail'])
                ->setMessage($dataForm['message'])
                ->setDepartement($dataForm['departement']);

                //Envoi du mail dans la bdd 
                $manager->persist($mail);
                $manager->flush();

                //Redirection vers la page contact avec un message de confirmation de l'envoi du mail
                return $this->render('contact.html.twig', [
                    'form' => $form,
                    'data' => $dataForm
                ]);

            }
        }
    }
}