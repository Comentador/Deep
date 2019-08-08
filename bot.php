<?php
    if(session_id() == ""){
        session_start();
    }

    require "BotC.php";

    define("BOT_TOKEN", "876737706:AAEaTouyw83yoHNP7s0gfmcRvx2b-vI9YbA");
    define("API_URL", "https://api.telegram.org/bot".BOT_TOKEN."/");
    define("WEBHOOK_URL", "https://consultador.herokuapp.com/bot.php");

    $conteudo = file_get_contents("php://input");
    $update = json_decode($conteudo, true);
    $mensagem = $update["message"];
    $opc;
    $opc["chat_id"]=$mensagem["chat"]["id"];
    $opc["texto"]=$mensagem["text"];
    $opc["message_id"]=$mensagem["message_id"];
    $opc["user_id"]= $mensagem["from"]["id"];
    $opc["first_name"]=$mensagem["from"]["first_name"];
    $opc["last_name"] = $mensagem["from"]["last_name"];
    $opc["user"] = $mensagem["from"]["username"];
    $opc["user_lang"]=$mensagem["from"]["language_code"];
    $opc["chat_type"] = $mensagem["chat"]["type"];

    $motor = new Divulga();
    $strings = new Strings();
    
    if(isset($update["callback_query"])){
        $motor->callback($opc ,$update["callback_query"]);
    }

    if($opc['texto'] === "/start"){
        $motor->sendMessage($opc, $opc['user']);
        
    }
    else if($opc["texto"] === "/tool"){
        $motor->sendChatAction($opc, "typing");
        $motor->foreca($opc);
    }

?>

