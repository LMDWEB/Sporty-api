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
                'requests' => 100,
                'description' => '100 requêtes',
            ],

            2 => [
                'price' => 15,
                'name' => 'Pro',
                'requests' => 1000,
                'description' => '1000 requêtes',
            ],

            3 => [
                'price' => 29,
                'name' => 'Entreprise',
                'requests' => 1000,
                'description' => '10000 requêtes',
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
    public function payment($id , RoleRepository $repository, ObjectManager $manager) {

        // Check if id forfait is valid

        if (in_array($id, array_keys($this->forfaits))) {

            // Free subscription

            if ($id == 1) {

                // Add Role to User

                $role = $repository->findOneBy(array('title' => 'ROLE_API'));
                $user = $this->getUser();
                $user->addUserRole($role);

                $user->setNbRequestMax($this->forfaits[1]['requests']);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash('success' , 'Vous etes maintenant souscrit au forfait : ' . $this->forfaits[1]['name']);

                return $this->render('price/index.html.twig');
            }

            // Pro et entreprise

            else {

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
                    ->setName('Forfait ' . $this->forfaits[$id]['name'] . ' Sporty API')
                    ->setPrice($this->forfaits[$id]['price'])
                    ->setDescription($this->forfaits[$id]['description'] . ' avec l\'API Sporty')
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

        // Add Role to User and nb request

        $role = $repository->findOneBy(array('title' => 'ROLE_API'));
        $user = $this->getUser();
        $user->addUserRole($role);

        $user->setNbRequestMax($this->forfaits[$id]['requests']);

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
