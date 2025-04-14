<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slug;
    private $em;

    /**
     * @param $slug
     */
    public
    function __construct (SluggerInterface $slug,EntityManagerInterface $manager )
    {
        $this -> slug = $slug;
        $this -> em = $manager;
    }


    public function load( ObjectManager $manager): void
    {

        $faker = Factory::create( 'fr_FR' );
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce( $faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider( $faker));


       //creation de categories
        for ( $c = 0 ; $c < 3 ; $c++ ) {
            $category = new Category();
            $category -> setName( $faker -> department() )
                      -> setSlug( $this -> slug -> slug( $category -> getName() ) );
            $this -> em -> persist( $category );


            //creation de produits
            for ( $i = 1 ; $i <= mt_rand( 15 , 20 ) ; $i++ ) {
                $product = new Product();
                $product -> setName( $faker -> productName() )
                    -> setPrice( $faker -> price( 4000 , 20000 ) )
                    -> setSlug( $this -> slug -> slug( $product -> getName() ) )
                    -> setDescription( $faker ->text(100) )
                    -> setPicture( $faker->imageUrl(400,400,true)  )
                    -> setCategory( $category );

                $this -> em -> persist( $product );
            }
        }


        $this->em->flush();
    }
}
