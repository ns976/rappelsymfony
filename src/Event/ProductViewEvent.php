<?php

    namespace App\Event;

    use App\Entity\Product;
    use Symfony\Contracts\EventDispatcher\Event;

    class ProductViewEvent extends  Event
    {

        const EVENT_PRODUCT_VIEW = 'product_view';
        private Product $product;

        public
        function __construct (Product $product)
        {
            $this->product = $product;

        }


        public function getProduct(){
            return $this->product;
        }
    }