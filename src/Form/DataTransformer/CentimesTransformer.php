<?php

    namespace App\Form\DataTransformer;

    use Symfony\Component\Form\DataTransformerInterface;

    class CentimesTransformer implements DataTransformerInterface
    {

        /**
         * @inheritDoc
         */
        public
        function transform ( $value )
        {
           if( $value ===  NULL  ){
              return;
           }
            return $value / 100;
        }

        /**
         * @inheritDoc
         */
        public
        function reverseTransform ( $value )
        {
            if($value === null){
                return;
            }
            return $value *100;
        }

    }