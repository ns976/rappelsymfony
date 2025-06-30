<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Entity\User;
use Cocur\Slugify\Slugify;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slug;
    private $em;
    private $UserPasswordEncoder;

    /**
     * @param $slug
     */
    public
    function __construct (SluggerInterface $slug,EntityManagerInterface $manager , UserPasswordHasherInterface $UserPasswordEncoder )
    {
        $this -> slug = $slug;
        $this -> em = $manager;
        $this -> UserPasswordEncoder = $UserPasswordEncoder;
    }


    public function load( ObjectManager $manager  ): void
    {
        $users =[];

        $faker = Factory::create( 'fr_FR' );
        $faker->addProvider(new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce( $faker));
        $faker->addProvider(new \Bluemmb\Faker\PicsumPhotosProvider( $faker));
        //creation d'un admin
        $admin = new User();
        $hash = $this->UserPasswordEncoder->hashPassword( $admin , 'password' );
        $admin->setEmail( 'admin@gmail.com' )
            -> setRoles(['ROLE_ADMIN'])
            -> setFullname( 'administrateur' )
            ->setPassword( $hash );
        $this -> em -> persist( $admin );
        $users [] = $admin;


        //creation de user
        for($u=0;$u<5;$u++){
            $user = new User();
            $hash = $this->UserPasswordEncoder->hashPassword( $user , 'password' );
            $user -> setEmail( 'user'.$u.'@gmail.com' )
                -> setRoles(['ROLE_USER'])
                -> setFullname( $faker->name().' '.$faker->firstName()  )
                -> setPassword(  $hash);
            $this -> em -> persist( $user );
            $users [] = $user;

        }
        $productList =[];
        //creation de categories
        for ( $c = 0 ; $c < 3 ; $c++ ) {
            $category = new Category();
            $category -> setName( $faker -> department() )
                //-> setSlug( $this -> slug -> slug( $category -> getName() ) )
                ->setOrwner( $faker->randomElement($users));

            $this -> em -> persist( $category );

            //creation de produits
            for ( $i = 1 ; $i <= mt_rand( 15 , 20 ) ; $i++ ) {
                $product = new Product();
                $product -> setName( $faker -> productName() )
                    -> setPrice( $faker -> price( 4000 , 20000 ) )
                   // -> setSlug( $this -> slug -> slug( $product -> getName() ) )
                    -> setDescription( $faker ->text(100) )
                    -> setPicture( $faker->imageUrl(400,400,true)  )
                    -> setUserCreate($faker->randomElement($users) )
                    -> setQuantite( mt_rand(4,25))
                    -> setCategory( $category );
                $productList[] = $product;
                $this -> em -> persist( $product );
            }
        }


        for($p=0;$p<=mt_rand(3,20);$p++){
            $purchase = new Purchase();
            $purchase->setFullname( $faker->name)
                    ->setAdresse( $faker->streetAddress)
                    ->setCodePostal( $faker->postcode)
                    ->setCity( $faker->city)
                    ->setPuchaseAt( DateTimeImmutable::createFromMutable($faker->dateTimeBetween('-6 months')));
                $Products = $faker->randomElements($productList,mt_rand(3,5));
                foreach ($Products as $product){
                   $purchaseItem = new PurchaseItem();
                   $purchaseItem->setProduct($product)
                                ->setQuantite( mt_rand(1,3))
                                ->setProductName( $product->getname())
                                ->setProductPrice( $product->getPrice())
                                ->setTotal( $product->getPrice() * $purchaseItem->getQuantite())
                                ->setPurchase($purchase);
                    $this->em->persist($purchaseItem);
                }

           if( $faker->boolean(90)){
               $purchase->setStatut( Purchase::STATUT_PAYER);
            }
          $purchase ->setUser(  $faker->randomElement($users));
          $purchase ->setTotal(  mt_rand(20000,30000));
           $this->em->persist($purchase);
        }

        $this->em->flush();
    }
}
