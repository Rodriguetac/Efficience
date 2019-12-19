<?php
namespace App\Form;

use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;


class ContactType extends AbstractType
{
    //Formulaire d'inscription
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $name_departement = array();
        $departement = Doctrine::getTable('Departement')->findAll();
        foreach($departement as $data)
        {
            $name_departement[] = $data['nom'];
        }

        $builder

            ->add('lastname', TextType::class, array('label'=> 'Nom', 'required' => true, 'attr' => array('class'=>'form-group', 'placeholder'=>'Entrez votre Nom')))
            ->add('firstname', TextType::class, array('label'=> 'Prénom', 'required' => true, 'attr' => array('class'=>'form-group ', 'placeholder'=>'Entrez votre Prénom')))
            ->add('Département', ChoiceType::class, array('label' => 'Departement', 'required' => true, 'choices' => []))
            ->add('mail', EmailType::class, array('label'=> 'Prix', 'required' => true, 'attr' => array('class'=>'form-group ','placeholder'=>'Entrez votre email')))
            ->add('message', TextType::class, array('label'=> 'Message', 'required' => true, 'attr' => array('class'=>'form-group ','placeholder'=>'Entrez le contenu de votre Email')))
        ;
    }
}