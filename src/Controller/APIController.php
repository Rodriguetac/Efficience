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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    public function addMail(Request $request, MailerInterface $mailer)
    {
        $manager = $this->getDoctrine()->getManager();

        $donnee = json_decode($request->getContent());

        $departement = $this->getDoctrine()
        ->getRepository(Departement::class)
        ->find($donnee->departement);

        $mail = (new Mail())
        ->setNom($donnee->nom)
        ->setPrenom($donnee->prenom)
        ->setMail($donnee->mail)
        ->setMessage($donnee->message)
        ->setDepartement($departement); 

        $manager->persist($mail);
        $manager->flush();

        $this->sendMail(
            $mailer,
            $donnee->mail, 
            $departement->getMailResponsable(),
            'Mail envoyé par ' . $donnee->prenom,
            'Mail envoyé par ' . $donnee->mail . ' à propos de : ' . $donnee->message
        );

        $response = new Response('OK', 201);

        return $response;
    }

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
