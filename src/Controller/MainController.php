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
        $form = $this->createForm(ContactType::class);

        //Si la méthode est POST je récupère les données du formulaire s'il a été soumis je crée un mail qui sera envoyé au responsable du département
        if($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            
            if($form->isSubmitted()){

                //Obtention des données du formulaire
                $dataForm = $form->getData();

                /*$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "localhost:8000/api/departement/" . $dataForm['departement']->getId());
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
                $return = curl_exec($ch);
                curl_close($ch);
                
                $json = ;
                dd(json_decode($json));
                $request = Request::create('localhost:8000/api/mail/ajout', 'POST', [], [], [], [], $jsonData);
                $header = array(
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($jsonData)   
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'localhost:8000/api/mail/ajout');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_HEADER, $header);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $jsonData);
                $return = curl_exec($ch);
                curl_close($ch);*/
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