<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ContactType;
use App\Entity\Departement;

class MainController extends AbstractController
{
    /**
     * @Route("/contact")
     */
    public function contact(Request $request)
    {
        $em = $this->getDoctrine()->getManager()->getRepository('App:Departement');
        $donne = $em->findAll();
        $form = $this->createForm(ContactType::class);

        if($request->isMethod('GET'))
        {
            return $this->render('contact.html.twig', [
                'form' => $form->createView()
            ]);
        }else if($request->isMethod('POST'))
        {
            
        }
    }
}