<?php

namespace App\Form;

use App\Entity\Purchase;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class CartConfirmationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

       $builder ->add('fullname',TextType::class,
           ['label'=> 'Nom  et prenom complet',
            'attr'=>['placeholder' => 'Nom complet pour la livraison' ],
            'required'=>false
           ]
       )
        ->add('adresse',TextType::class,
            ['label'=> 'Adresse de livraison',

             'attr'=>['placeholder' => '123 rue de l\'Exemple' ] ,
             'required'=>false
            ]
        )
        ->add('codePostal',TextType::class,
            ['label'=> 'Code postal',
             'attr'=>['placeholder' => 'Votre code postal','maxlength' =>5],
             'required'=>false,
            ]
        )
        ->add('city',TextType::class,
            [
                'label'=> 'Ville',
                'attr'=>['placeholder' => 'Ville de livraison'],
                'required'=>false
            ] );

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Purchase::class,
            'method'=>"POST"
        ]);
    }
}
