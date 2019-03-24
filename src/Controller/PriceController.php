<?php

namespace App\Controller;

use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;
use PayPal\Rest\ApiContext;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Dotenv\Dotenv;

class PriceController extends AbstractController
{
    /**
     * @Route("/price", name="price")
     */
    public function index()
    {
        return $this->render('price/index.html.twig', []);
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function payment(){

        $dotenv = new Dotenv();
        $dotenv->load('./../.env');
        $id = getenv('PAYPAL_ID');
        $secret = getenv('PAYPAL_SECRET');

        $ids = [
            'id' => $id,
            'secret' => $secret
        ];

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $ids['id'],
                $ids['secret']
            )
        );

        //$apiContext->setConfig([
        //    'mode' => 'live',
        //]);

        $list = new ItemList();
        $item = (new Item())
            ->setName('nomduproduit')
            ->setPrice(1.00)
            ->setCurrency('EUR')
            ->setQuantity(1);
        $list->addItem($item);

        $details = (new Details())
            ->setSubtotal(1.00);

        $amount = (new Amount())
            ->setTotal(1.00)
            ->setCurrency('EUR')
            ->setDetails($details);

        $transaction = (new Transaction())
            ->setItemList($list)
            ->setDescription("Achat sur monsite.fr")
            ->setAmount($amount)
            ->setCustom('demo-1');


        $payment = new Payment();
        $payment->setTransactions([$transaction]);
        $payment->setIntent('sale');
        $redirectUrls = (new RedirectUrls())
            ->setReturnUrl('http://localhost:8000/pay')
            ->setCancelUrl('http://localhost:8000/');
        $payment->setRedirectUrls($redirectUrls);
        $payment->setPayer((new Payer())->setPaymentMethod('paypal'));

        try {
            $payment->create($apiContext);
            header('Location: ' . $payment->getApprovalLink());
            exit();
        } catch (PayPalConnectionException $e){
            var_dump(json_decode($e->getData()));
        }

        return $this->render('price/index.html.twig', [
            'controller_name' => 'PriceController',
        ]);
    }

    /**
     * @Route("/pay", name="pay")
     */
    public function pay(){
        $dotenv = new Dotenv();
        $dotenv->load('./../.env');
        $id = getenv('PAYPAL_ID');
        $secret = getenv('PAYPAL_SECRET');

        $ids = [
            'id' => $id,
            'secret' => $secret
        ];

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $ids['id'],
                $ids['secret']
            )
        );

        //$apiContext->setConfig([
        //    'mode' => 'live',
        //]);

        $payment = Payment::get($_GET['paymentId'], $apiContext);

        $execution = (new PaymentExecution())
            ->setPayerId($_GET['PayerID'])
            ->setTransactions($payment->getTransactions());

        try {
            $payment->execute($execution, $apiContext);
            //var_dump($payment->getTransactions()[0]->getCustom());
            //var_dump($payment);
        } catch (PayPalConnectionException $e){
            var_dump(json_decode($e->getData()));
        }

        return $this->render('price/confirm.html.twig', [
            'payment' => $payment,
        ]);
    }
}
