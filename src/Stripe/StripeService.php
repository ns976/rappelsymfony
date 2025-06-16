<?php
 namespace App\Stripe;

    use App\Entity\Purchase;
    use Stripe\PaymentIntent;
    use Stripe\StripeClient;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


    class StripeService extends AbstractController
    {

        private  string $stripe_public_key;
        private  string $stripe_secret_key;

        public
        function __construct (string $stripe_public_key ,string $stripe_secret_key)
        {
            $this->stripe_public_key = $stripe_public_key;
            $this->stripe_secret_key = $stripe_secret_key;
        }


        public function getPaymentIntent(Purchase $purchase) : PaymentIntent{
            $stripe = new StripeClient($this->stripe_secret_key);
            return  $stripe->paymentIntents->create([
                'amount' =>( $purchase->getTotal()*100),
                'currency' => 'eur',
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ]);

        }

        public function getStripePublicKey() : string{
            return $this->stripe_public_key;
        }
    }
