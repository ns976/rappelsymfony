<?php

    namespace App\cart;

    use App\Repository\ProductRepository;
    use Symfony\Component\HttpFoundation\Session\SessionInterface;

    class CartService
    {

        private $session;
        private $productRepository;

        public function __construct(SessionInterface $session,ProductRepository $productRepository){
            $this->session = $session;
            $this->productRepository = $productRepository;
        }

        /**
         * Récupère le panier depuis la session ou retourne un tableau vide si le panier n'existe pas
         *
         * @return array
         */
        public function getCart(): array
        {
            // Récupérer le panier depuis la session
            return $this->session ->get('cart', []);
        }

        /**
         * Met à jour le panier dans la session
         *
         * @param array $cart
         */
        public function setCart(array $cart): void
        {
            // Mettre à jour le panier dans la session
            $this->session->set('cart', $cart);
        }

        /**
         * Retoure le total d'un panier
         *
         */
        public function totalCart(): float{
            $cart = $this->getCart();
            $total = 0;

            foreach ($cart as $idproduct => $quantity) {
                // Assuming you have a method to get the product price by its ID
                $productPrice = $this->getProductPrice($idproduct);
                $total += $productPrice * $quantity;
            }

            return $total;
        }

        /**
         * Retourne le total d'un produit dans le panier
         *
         * @param int $idproduct
         * @return float
         */
        public function totalProduct(int $idproduct): float
        {
            // Récupérer le panier depuis la session
            $cart = $this->getCart();
            // Vérifier si le produit est dans le panier
            if (array_key_exists($idproduct, $cart)) {
                // Récupérer la quantité du produit dans le panier
                $quantity = $cart[$idproduct];
                // Récupérer le prix du produit
                $productPrice = $this->getProductPrice($idproduct);
                // Calculer le total pour ce produit
                return $productPrice * $quantity;
            }
            return 0; // Si le produit n'est pas dans le panier, retourner 0
        }

        /**
         * Récupère le prix d'un produit par son ID
         *
         * @param int $idproduct
         *
         * @return float|void
         */
        private
        function getProductPrice ( int $idproduct )
        {
            $product = $this->productRepository->find($idproduct);
            if(!$product) {
                // Si le produit n'existe pas, retourner 0
                return 0;
            }
            return $product->getPrice();
        }

        public function nbrItems(): int
        {
            $cart = $this->getCart();
            $totalItems = 0;

            foreach ($cart as $quantity) {
                $totalItems += $quantity;
            }

            return $totalItems;
        }
    }