<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Entity\Mail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/contact")
     */
    public function contact(Request $request)
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
                
                $dataForm['departement'] = $dataForm['departement']->getId();
                
                $jsonData = json_encode($dataForm);

                $header = array(
                    'content-type: application/json',
                    'Content-Length: ' . strlen($jsonData)   
                );
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'localhost:8000/api/mail/ajout');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_HEADER, $header);
                curl_setopt($ch,CURLOPT_POSTFIELDS, $jsonData);

                $return = curl_exec($ch);
                curl_close($ch);
                
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
}