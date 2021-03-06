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
        $mail = new Mail();
        //Création du formulaire
        $form = $this->createForm(ContactType::class, $mail);

        //Si la méthode est POST je récupère les données du formulaire s'il a été soumis je crée un mail qui sera envoyé au responsable du département
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            
            if($form->isSubmitted()){

                //Obtention des données du formulaire
                $dataForm = $form->getData();

                //Appel de la fonction sendMail
                $this->sendMail(
                    $mailer,
                    $dataForm->getMail(), 
                    $dataForm->getDepartement()->getMailResponsable(),
                    'Mail envoyé par ' . $dataForm->getPrenom(),
                    'Mail envoyé par ' . $dataForm->getMail() . ' à propos de : ' . $dataForm->getMessage()
                );

                $manager = $this->getDoctrine()->getManager();

                //Envoi du mail dans la bdd 
                $manager->persist($mail);
                $manager->flush();
            }
            //Affichage de la page contact.html.twig avec le formulaire
            return $this->render('contact.html.twig', [
                'form' => $form->createView(),
                'data' => $dataForm
            ]);

        }
        else
        {
            //Affichage de la page contact.html.twig avec le formulaire
            return $this->render('contact.html.twig', [
                'form' => $form->createView()
            ]);
        }
        
    }

    //Fonction d'envoie de Mail
    public function sendMail(MailerInterface $mailer, $from, $to, $subject, $text)
    {
        $mail = (new Email())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->text($text);

        $sentEmail = $mailer->send($mail);
    }
}