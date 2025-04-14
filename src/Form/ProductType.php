<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Faker\Factory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $faker = Factory ::create( 'fr_FR' );
        $faker -> addProvider( new \Bluemmb\Faker\PicsumPhotosProvider( $faker ) );
        $image_default = empty($builder->getData()) ? $faker -> imageUrl( 200 , 200 , true )  :$builder->getData()->getImg();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'label_attr' => ['class' => 'form-label fw-bold'],
                'required' => false,
                'help' => 'Le nom du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir le nom du produit'
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Le prix du produit',
                'required' => false,
                'help' => 'Le prix du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir le prix du produit'
                ],
            ])
            ->add('picture', UrlType::class, [
                'label' => 'Image',
                'data' => $image_default,
                'help' => 'L’URL de l’image',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'https://exemple.com/image.jpg'
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du produit',
                'required' => false,
                'help' => 'La description du produit',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Saisir la description du produit',
                    'rows' => 5
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'required' => false,
                'help' => 'La catégorie rattachée au produit',
                'placeholder' => '-- Choisir une catégorie --',
                'class' => Category::class,
                'choice_label' => function (Category $category) {
                    return strtoupper($category->getName());
                },
                'attr' => [
                    'class' => 'form-select'
                ],
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
