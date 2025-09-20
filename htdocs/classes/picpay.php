<?php
if (!defined('INITIALIZED'))
	exit;

class PicPay
{

	/*
	  *@var type String: $urlCallBack
	  */
	private $urlCallBack = 'http://on.quelibra.online/picpay_notfication.php';

	/*
	   *@var type String: $urlReturn
	   */
	private $urlReturn = 'https://quelibra.online/';

	/*
	  *@Get token: https://bit.ly/2XyAWCy
	  *@var type String: $x_picpay_token
	  */
	private $x_picpay_token = 'd12d67ce-3d6b-4d1d-ba6a-955cdcfc5e04';

	/*
	   *@var type String: $x_seller_token
	   */
	private $x_seller_token = 'cf1359b3-96fe-4b3f-9fd9-d78f51c49558';



	//Função que faz a requisição
	public function requestPayment($produto, $cliente)
	{

		$data = array(
			'referenceId' => $produto->ref,
			'callbackUrl' => $this->urlCallBack,
			'returnUrl'   => $this->urlReturn,
			'value'       => $produto->valor,
			'buyer'       => [
				'firstName' => $cliente->nome,
				'lastName'  => $cliente->sobreNome,
				'document'  => $cliente->cpf,
				'email'     => $cliente->email,
				'phone'     => $cliente->telefone
			],
		);

		$ch = curl_init('https://appws.picpay.com/ecommerce/public/payments');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-picpay-token: ' . $this->x_picpay_token));

		$res = curl_exec($ch);
		curl_close($ch);
		$return = json_decode($res);

		return $return;
	}



	// Notificação PicPay
	public function notificationPayment()
	{

		$content = trim(file_get_contents("php://input"));
		$payBody = json_decode($content);

		if (isset($payBody->authorizationId)) :

			$referenceId = $payBody->referenceId;

			$ch = curl_init('https://appws.picpay.com/ecommerce/public/payments/' . $referenceId . '/status');
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('x-seller-token: ' . $this->x_seller_token));

			$res = curl_exec($ch);
			curl_close($ch);
			$notification = json_decode($res);

			$notification->referenceId     = $payBody->referenceId;
			$notification->authorizationId = $payBody->authorizationId;

			return $notification;

		else :

			return false;

		endif;
	}
}
