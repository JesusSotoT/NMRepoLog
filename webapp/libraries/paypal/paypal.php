<?php

	global $global_path;

	require_once 'bootstrap.php';
    use PayPal\Api\Amount;
    use PayPal\Api\Details;
    use PayPal\Api\Item;
    use PayPal\Api\ItemList;
    use PayPal\Api\Payer;
    use PayPal\Api\Payment;
    use PayPal\Api\RedirectUrls;
    use PayPal\Api\Transaction;
	use PayPal\Api\PaymentExecution;
	use PayPal\Api\Address;
    use PayPal\Api\BillingInfo;
    use PayPal\Api\Cost;
    use PayPal\Api\Currency;
    use PayPal\Api\Invoice;
    use PayPal\Api\InvoiceAddress;
    use PayPal\Api\InvoiceItem;
    use PayPal\Api\MerchantInfo;
    use PayPal\Api\PaymentTerm;
    use PayPal\Api\Phone;
    use PayPal\Api\ShippingInfo;

	class PhpPayPal
	{

		private $payer;
		private $items;
		private $details;
		private $amount;
		private $transaction;
		private $redirect_urls;
		private $payment;

		function __construct($type = "paypal")
		{
			$this->payer = new Payer();
			$this->payer->setPaymentMethod($type);
			$this->items = new ItemList();
			$this->details = new Details();
			$this->amount = new Amount();
			$this->transaction = new Transaction();
			$this->redirect_urls = new RedirectUrls();
			$this->payment = new Payment();
		}

		function __destruct()
		{

		}

		public function setItems($items)
		{
			$list = array();
			foreach ($items as $element) {
		        $item = new Item();
		        $item->setName($element["producto"])->setCurrency('MXN')->setQuantity(1)->setSku($element["id"])->setPrice($element["precio"]);
		        $list[] = $item;
		    }
		    $this->items->setItems($list);
		}

		public function setDetails($amount)
		{
			$this->details->setShipping(0)->setTax(0)->setSubtotal($amount);
			$this->amount->setCurrency("MXN")->setTotal($amount)->setDetails($this->details);
		}

		public function setTransaction($description)
		{
			$this->transaction->setAmount($this->amount)->setItemList($this->items)->setDescription($description)->setInvoiceNumber(uniqid());
		}

		public function setRedirectUrls($accept, $cancel)
		{
			$this->redirect_urls->setReturnUrl($accept)->setCancelUrl($cancel);
		}

		public function setUpPayment()
		{
			try {
				global $apiContext;
				$this->payment->setIntent("sale")->setPayer($this->payer)->setRedirectUrls($this->redirect_urls)->setTransactions(array($this->transaction));
		        $this->payment->create($apiContext);
		        $status = array("status" => true);
		    } catch (Exception $e) {
		    	$status = array("status" => false, "message" => $e->getMessage());
		    }
		    return $status;
		}

		function createInvoice($note, $customer, $items) {
	        global $apiContext;
	        try {
	            $invoice = new Invoice();
	            $invoice
	                ->setMerchantInfo(new MerchantInfo())
	                ->setBillingInfo(array(new BillingInfo()))
	                ->setNote($note)
	                ->setPaymentTerm(new PaymentTerm())
	                ->setShippingInfo(new ShippingInfo());

	            $invoice->getMerchantInfo()
	                ->setEmail("evesmax-facilitator@netwaremonitor.com")
	                ->setbusinessName("Netwarmonitor")
	                ->setAddress(new Address());

	            $invoice->getMerchantInfo()->getAddress()
	                ->setLine1("Av. 18 de Marzo #287")
	                ->setCity("Guadalajara")
	                ->setState("Jalisco")
	                ->setPostalCode("44470")
	                ->setCountryCode("MX");

	            $billing = $invoice->getBillingInfo();
	            $billing[0]->setEmail($customer["email"]);

	            foreach ($items as &$element) {
	                $item = new InvoiceItem();
	                $item->setName($element["producto"])->setQuantity(1)->setUnitPrice(new Currency());
	                $item->getUnitPrice()->setCurrency("MXN")->setValue($element["precio"]);
	                $element = $item;
	            }
	            $invoice->setItems($items);

	            $invoice->getPaymentTerm()->setTermType("DUE_ON_DATE_SPECIFIED");

	            $invoice->getShippingInfo()
	                ->setFirstName($customer["nombre"])
	                ->setBusinessName($customer["razon"]);

	            $invoice->setLogoUrl('https://www.paypalobjects.com/webstatic/i/logo/rebrand/ppcom.svg');

	            $invoice->create($apiContext);
	            $invoice->send($apiContext);
	            $invoice = Invoice::get($invoice->getId(), $apiContext);

	            $result = array("status" => true, "reference" => $invoice->getId(), "url" => $invoice->getMetadata()->getPayerViewUrl());
	        } catch (Exception $e) {
	            $result = array("status" => false, "message" => $e->getMessage());
	        }
	        return $result;
	    }

	    public function getInvoice($invoice)
	    {
	    	global $apiContext;
	        try {
	            $invoice = Invoice::get($invoice, $apiContext);
	        } catch (Exception $e) {
	            $invoice = false;
	        }
	        return $invoice;
	    }

		public function pay($payer, $payment)
		{
			try {
				global $apiContext;
                $payment = Payment::get($payment, $apiContext);
                $request = new PaymentExecution();
                $request->setPayerId($payer);
                $result = $payment->execute($request, $apiContext);
                $payment = Payment::get($payment->getId(), $apiContext);
                $this->payment = $payment;
                $status = array("status" => true);
            } catch(Exception $e) {
            	$pagado = false;
                if(method_exists($e, "getData")){
                    $error = json_decode($e->getData());
                    $pagado = $error->name == "PAYMENT_ALREADY_DONE";
                }
                $error = (method_exists($e, "getData")) ? json_decode($e->getData())->name : $e->getMessage();
                $status = array("status" => false, "message" => $error, "pagado" => $pagado);
            }
            return $status;
		}

		public function getPaymentLink()
		{
			return $this->payment->getApprovalLink();
		}

		public function getReference()
		{
			return $this->payment->getId();
		}

	}

?>