<?php
// WePay PHP SDK - http://git.io/mY7iQQ
require 'wepay.php';
// application settings
$account_id    = 1042818226;
$client_id     = 99163;
$client_secret = "19e7fbcb24";
$access_token  = "PRODUCTION_ac68324ea6df6e0b85ae5e61a32793e05cde230e34b92b4f6365807bdd0d91e9";
// credit card id to charge
$credit_card_id = 213252971;
// change to useProduction for live environments
Wepay::useProduction($client_id, $client_secret);
$wepay = new WePay($access_token);
// charge the credit card
$response = $wepay->request('checkout/create', array(
    'account_id'          => $account_id,
    'amount'              => '10.00',
    'currency'            => 'USD',
    'short_description'   => 'A vacation home rental',
    'type'                => 'goods',
    'payment_method'      => array(
        'type'            => 'credit_card',
        'credit_card'     => array(
            'id'          => $credit_card_id
        )
    )
));
// display the response
print_r($response);
?>
