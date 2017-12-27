<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// PayPal classes
use PayPal\Api\Item;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Payment;
use PayPal\Api\Details;
use PayPal\Api\ItemList;
use PayPal\Rest\ApiContext;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Exception\PayPalConnectionException;

class PurchaseController extends Controller
{
	private $paypal;

	public function __construct()
	{
		$this->paypal = new ApiContext(new OAuthTokenCredential(
    		config('services.paypal.client_id'),
    		config('services.paypal.secret')
    	));
	}

	/**
	 * Run after click buy button
	 * On success everything it will redirect to paypal
	 */
    public function checkout(Request $request)
    {
    	// Get products informations from request
    	// dd($request->all());

    	// Create demo product data
    	$product = 'Sourov Test Product';
    	$price = 5;
    	$shipping = 1;
    	$total = number_format(($price + $shipping), 2);

    	// Set payment method
    	$payer = new Payer();
        $payer->setPaymentMethod('paypal');

        // Set item
        $item = new Item();
		$item->setName($product)
			->setCurrency('USD')
			->setQuantity(1)
			->setDescription('Sourov test description')
			->setPrice($price);

		// Add item to the item list
		$itemList = new ItemList();
		$itemList->setItems([$item]);

		// Set checkout details, like shipping, tax, fee etc.
		// Here set subtotal = sum all items price
		$details = new Details();
		$details->setShipping($shipping)
			->setSubtotal($price);

		// Set amount for transaction
		$amount = new Amount();
		$amount->setCurrency('USD')
			->setTotal($total)
			->setDetails($details);

		// Prepare to transaction
		$transaction = new Transaction();
		$transaction->setAmount($amount)
			->setItemList($itemList)
			->setInvoiceNumber('SR-'.uniqid());

		// Set redirect urls
		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl(route('paypal.verify.success'))
			->setCancelUrl(route('home', ['error_message' => 'You are cancel this payment.']));

		// Get all togather to pay
		$payment = new Payment();
		$payment->setIntent('sale')
			->setPayer($payer)
			->setTransactions([$transaction])
			->setRedirectUrls($redirectUrls);

		// Now send request to paypal
		try {
			$payment->create($this->paypal);
		}catch(\PayPal\Exception\PayPalConnectionException $ex){
			dd( json_decode($ex->getData()) );
		}

		$approvalLink = $payment->getApprovalLink();
		return redirect($approvalLink);

    }// checkout

    /**
     * Verify user and make payment
     */
    public function verifySuccess(Request $request)
    {
    	$paymentId = $request->get('paymentId');
    	$PayerID = $request->get('PayerID');
    	if(empty($PayerID) || empty($paymentId)){
    		return redirect()->route('home')->with('error_message', 'Sorry!, Unable to get paypal information.');
    	}

    	$payment = Payment::get($paymentId, $this->paypal);
    	$execution = new PaymentExecution();
        $execution->setPayerId($PayerID);

        try{
        	$result = $payment->execute($execution, $this->paypal);
        }catch(Exception $e){
        	dd($e);
        }
		
		if($result->getState() == 'approved'){
			// Database logic will be here

			// Redirect any page to show payment and product information
			return redirect()->route('home')->with('success_message', 'You have successfully paid.');
		}else{
			return redirect()->route('home')->with('error_message', 'Sorry!, Unable to process payment.');
		}
        
    }// verifySuccess

}
