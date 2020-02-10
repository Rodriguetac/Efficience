<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Entity\Mail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/api", name="api_")
 */
class APIController extends AbstractController
{
    /**
     * @Route("/departement", name="departement", methods={"GET"})
     */
    public function departement()
    {
        $departements = $this->getDoctrine()
        ->getRepository(Departement::class)
        ->apiFindAll();

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($departements, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/departement/{id}", name="departement_id", methods={"GET"})
     */
    public function departement_id($id)
    {
        $departement = $this->getDoctrine()
        ->getRepository(Departement::class)
        ->apiFindId($id);

        $encoders = [new JsonEncoder()];

        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $jsonContent = $serializer->serialize($departement, 'json');

        $response = new Response($jsonContent);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
    /**
     * @Route("/mail/ajout", name="mail_ajout", methods={"POST"})
     */
    public function addMail(Request $request)
    {
       /* if($request->isXmlHttpRequest())
        {*/
            $donnee = json_decode($request->getContent());
            dd($request->getContent());
            dd($donnee->departement);
            $mail = (new Mail())
            ->setNom($donnee->nom)
            ->setPrenom($donnee->prenom)
            ->setMail($donnee->mail)
            ->setMessage($donnee->message)
            ->setDepartement($donnee->departement); 

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($mail);
            $manager->flush();

            return new Response('OK', 201); 
        /*}
        return new Response('Erreur', 404);*/
    }
}
