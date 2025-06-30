<?php

    declare( strict_types = 1 );

    namespace App\twigExtension;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFunction;
    use Twig\TwigFilter;

    class Arrondi extends AbstractExtension
    {
        public
        function getFunctions () : array
        {
//            return [ new TwigFunction( 'my_function' , $this -> myFunction( ... ) ) ,
//            ];
            return [];
        }

        public
        function getFilters () : array
        {
            return [ new TwigFilter( 'arrondie' ,[ $this , 'arrondie'] ) ,
            ];
        }

        public
        function arrondie ( int $value,string $symbol='€',string $separator=',',string $thousandsSeparator=' ') : string
        {
            return number_format( $value , 2 , $separator , $thousandsSeparator ) . $symbol;
        }
    }
