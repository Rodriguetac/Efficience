<?php
namespace App\Form;

use App\Entity\Departement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formulaire de Contact
 */
class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Création des inputs de la liste déroulante et du bouton de Validation
        $builder

            ->add('nom', TextType::class, 
                array(
                    'label'=> 'Nom', 
                    'required' => true, 
                    'attr' => 
                        array(
                            'class'=>'form-group', 
                            'placeholder'=>'Entrez votre Nom'
                        ) 
                )
            )

            ->add('prenom', TextType::class, 
                array(
                    'label'=> 'Prénom', 
                    'required' => true, 
                    'attr' => 
                        array(
                            'class'=>'form-group ', 
                            'placeholder'=>'Entrez votre Prénom'
                        )
                )
            )

            ->add('departement', EntityType::class, 
                array(
                    'class' => Departement::class, 
                    'choice_label'=> 'Nom' 
                )
            )

            ->add('mail', EmailType::class, 
                array(
                    'label'=> 'Mail', 
                    'required' => true, 
                    'attr' => 
                        array(
                            'class'=>'form-group ', 
                            'placeholder'=>'Entrez votre email'
                        )
                )
            )

            ->add('message', TextareaType::class, 
                array(
                    'label'=> 'Message', 
                    'required' => true, 
                    'attr' => 
                        array(
                            'class'=>'textMessage',
                            'placeholder'=>'Entrez le contenu de votre Email'
                        )
                )
            )

            ->add('submit', SubmitType::class, 
                array(
                    'label' => 'Envoi du mail', 
                    'attr' => 
                        array(
                            'class' => 'btn btn-primary'
                        )
                )
            );
    }
}