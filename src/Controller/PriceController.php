<?php

namespace App\Controller;

use App\Repository\RoleRepository;
use Doctrine\Common\Persistence\ObjectManager;
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
use PhpParser\Node\Expr\Cast\Object_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class PriceController extends AbstractController
{

    private  $forfaits = [
            1 => [
                'price' => 0,
                'name' => 'Free',
                'description' => 'Free Plan',
                'role' => 'ROLE_FREE'
            ],

            2 => [
                'price' => 15,
                'name' => 'Pro',
                'description' => 'Pro Plan',
                'role' => 'ROLE_PRO'
            ],

            3 => [
                'price' => 29,
                'name' => 'Entreprise',
                'description' => 'Entreprise Plan',
                'role' => 'ROLE_ENTREPRISE'
            ],
        ];
    /**
     * @Route("/price", name="price")
     */
    public function index()
    {
        return $this->render('price/index.html.twig');
    }

    /**
     * @Route("/payment/{id}", name="payment", )
     */
    public function payment($id) {

        // Check if id forfait is valid

        if (in_array($id, array_keys($this->forfaits))) {

            $dotenv = new Dotenv();
            $dotenv->load('./../.env');

            $apiContext = new ApiContext(
                new OAuthTokenCredential(
                    getenv('PAYPAL_ID'),
                    getenv('PAYPAL_SECRET')
                )
            );

            $apiContext->setConfig(array('mode' => ( getenv('PAYPAL_MODE') )));

            $list = new ItemList();
            $item = (new Item())
                ->setName($this->forfaits[$id]['name'])
                ->setPrice($this->forfaits[$id]['price'])
                ->setDescription($this->forfaits[$id]['description'])
                ->setCurrency('EUR')
                ->setQuantity(1);
            $list->addItem($item);

            $details = (new Details())
                ->setSubtotal($this->forfaits[$id]['price']);

            $amount = (new Amount())
                ->setTotal($this->forfaits[$id]['price'])
                ->setCurrency('EUR')
                ->setDetails($details);

            $transaction = (new Transaction())
                ->setItemList($list)
                ->setDescription("Acces API Sporty  : " . $this->forfaits[$id]['name'] )
                ->setAmount($amount)
                ->setCustom($id);

            $payment = new Payment();
            $payment->setTransactions([$transaction]);
            $payment->setIntent('sale');
            $redirectUrls = (new RedirectUrls())
                ->setReturnUrl($this->generateUrl('pay', array(), UrlGeneratorInterface::ABSOLUTE_URL ))
                ->setCancelUrl($this->generateUrl('cancel', array(), UrlGeneratorInterface::ABSOLUTE_URL ));
            $payment->setRedirectUrls($redirectUrls);
            $payment->setPayer((new Payer())->setPaymentMethod('paypal'));

            try {
                $payment->create($apiContext);
                header('Location: ' . $payment->getApprovalLink());
                exit();
            } catch (PayPalConnectionException $e){

                // log the error
                //var_dump(json_decode($e->getData()));

                $this->addFlash('danger' , 'Oups, une erreur s\'est produite. Merci de réessayer plus tard.');
                return $this->render('price/index.html.twig');
            }
        }

        else {

            $this->addFlash('danger' , 'Oups, une erreur s\'est produite .');
            return $this->render('price/index.html.twig');
        }
    }

    /**
     * @Route("/pay", name="pay")
     */
    public function pay(RoleRepository $repository, ObjectManager $manager) {

        $dotenv = new Dotenv();
        $dotenv->load('./../.env');

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                getenv('PAYPAL_ID'),
                getenv('PAYPAL_SECRET')
            )
        );

        $apiContext->setConfig(array('mode' => ( getenv('PAYPAL_MODE') )));

        $payment = Payment::get($_GET['paymentId'], $apiContext);

        $id = $payment->getTransactions()[0]->getCustom();

        $execution = (new PaymentExecution())
            ->setPayerId($_GET['PayerID'])
            ->setTransactions($payment->getTransactions());

        try {
            $payment->execute($execution, $apiContext);

        } catch (PayPalConnectionException $e){

            // log the error
            //var_dump(json_decode($e->getData()));

            $this->addFlash('danger' , 'Oups, une erreur s\'est produite. Merci de réessayer plus tard.');
            return $this->render('price/index.html.twig');
        }

        // Add Role to User

        $role = $repository->findOneBy(array('title' => $this->forfaits[$id]['role']));
        $user = $this->getUser();
        $user->addUserRole($role);

        $manager->persist($user);
        $manager->flush();

        $this->addFlash('success' , 'Vous etes maintenant souscrit au forfait : ' . $this->forfaits[$id]['name']);

        return $this->render('price/index.html.twig');
    }

    /**
     * @Route("/cancel", name="cancel")
     */
    public function cancel () {

        $this->addFlash('info' , 'Votre commande a été annulé');
        return $this->render('price/index.html.twig');
    }
}
