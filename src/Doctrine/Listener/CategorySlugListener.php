<?php

    namespace App\Doctrine\Listener;

    use App\Entity\Category;
    use Symfony\Component\String\Slugger\SluggerInterface;


/*    Exercice : un slug automatique pour les catégories
        Le but est que le slug d'une nouvelle catégorie soit généré automatiquement lorsque l'on persiste une nouvelle catégorie.
          Exigences :
             Décidez quel est le meilleur moyen pour y arriver : LifecycleCallbacks (fonction directement dans la classe de entity) , Lifecycle Listener( appeler tous le temps sur l'evenement quelque soit entite)  ou Entity Listener ( une entite cible) ?
             Décidez à quel événement du cycle de vie vous voulez vous intéresser
             Mettez en place l'automatisme*/


    class CategorySlugListener
    {
        protected $slugger;

        public function __construct(SluggerInterface $slugger){
            $this->slugger = $slugger;
        }


        public function prePersist(Category $category)
        {
           if(empty($category->getSlug())){
               $category->setSlug($this->slugger->slug(  $category->getName()));

           }
        }
    }