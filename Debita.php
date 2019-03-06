<?php
	require "vendor/autoload.php";

	\stripe\stripe::setApiKey("sk_live_RQa5GxYEUp9tWfAWjMRtaEtR");
	$token = $_POST["stripeToken"];
	 $charge = \stripe::charge([
	 	"amount"=>"1500",
	 	"currency"=>"usd",
	 	"description"=>"Testanto stripe";
	 	"source"=>$token
	 ]);

	 echo "<pre>", print_r($charge), "</pre>";
?>