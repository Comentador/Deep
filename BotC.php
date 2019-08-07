<?php

class Divulga{

	public $str;
	public $id;
	public $db;

	public function __construct(){
		$this->str = new Strings();
		/*try{

			$this->db = new PDO("mysql:dbname=mozdev;host=35.178.176.93", "root", "dados123");
			

		}catch(PDOexception $e){
			return $e->getMessage();
		}*/
	}

	protected function apiRequest($metodo, $parametro){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, API_URL.$metodo."?");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
			"Content-Type" => "multipart/form-data"
		));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($parametro));
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$resultado = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
	}

	public function sendMessage($opc, $msg){

		$parametro = array(
			"chat_id"=>$opc["chat_id"],
			"disable_web_page_preview"=>1,
			"parse_mode"=>"Markdown",
			"text"=>$msg
		);

		$this->apiRequest("sendMessage", $parametro);
	}

	public function editMessage($opc, $msg){
		$parametro = array(
			"chat_id"=>$opc["chat_id"],
			"text"=>$msg,
			"message_id"=>$opc["message_id"],
		);

		$this->apiRequest("editMessageText", $parametro);

	}

	protected function answerCallbackQuery($callback_id, $alert, $time, $text){
		$parametro = array(
			"callback_query_id"=>$callback_id,
			"show_alert"=>$alert,
			"cache_time"=>$time,
			"text"=>$text,
		);

		$this->apiRequest("answerCallbackQuery", $parametro);
	}

	public function callback($opc ,$callback){
		$cb_chat_id = $callback["message"]["chat"]["id"];
		$cb_message_id = $callback["message"]["id"];
		$cb_id = $callback["id"];
		$cb_data = $callback["data"];


		if($cb_data == "avisei"){
			$text = null;
			$this->answerCallbackQuery($cb_id, false, 3, $text);
			$this->editMessage($opc, "Mudei o inline");
		}
		else if($cb_data == "pode"){
			$text = "Seja bem vindo";
			$this->answerCallbackQuery($cb_id, false, 3, $text);
		}
	}

	public function SendInline($opc, $msg, $button){
		$encode = json_encode($button, true);

		$parametro = array(
			"chat_id"=>$opc["chat_id"],
			"text"=>$msg,
			"reply_markup"=>$encode,
			"parse_mode"=>"Markdown",
		);

		$this->apiRequest("sendMessage", $parametro);
	}

	public function saveID($id){

		$abertura = fopen("id.txt", "w+");
		$conteudo = $id;
		$escrita = fwrite($abertura, $conteudo);
		fclose($abertura);
	}

	public function registrar($user, $lang, $passwd){
		
			$sql = $this->db->prepare("SELECT * FROM usuario WHERE user =".$user);
			$sql->execute();
			$much = array();
			if($sql->rowCount() > 0){
				
				return "O usuário ja esta cadastrado";
			
			}else{

				$sql = $this->db->prepare("INSERT INTO usuario SET user=:usr, senha=:passw, linguagem=:lang");
				$sql->bindValue(":usr", $user);
				$sql->bindValue(":passw", $lang);
				$sql->bindValue(":lang", $passwd);
				$sql->execute();

				return "Obrigado por se registrar. A mozDevs agradece...";
			}

	}

	public function lista ($linguagem, $tipo=null){

		$sql = $this->db->prepare("SELECT * FROM usuario WHERE linguagem=:lang");
		$sql->bindValue(":lang", $linguagem);
		$sql->execute();

		$list = array();

		if($sql->rowCount() > 0){
			$list = $sql->fetchAll();
		}

		return $list;
	}


}


class Strings{

	public $fala = array(
		"primeira"=>"opa fion",
		"menu"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"Registro", "callback_data"=>"reg")),
				array(array("text"=>"Procurar", "callback_data"=>"search"), array("text"=>"Parceria", "callback_data"=>"parser")),
				array(array("text"=>"Login", "callback_data"=>"log"), array("text"=>"Ferramentas", "callback_data"=>"tools")),
				array(array("text"=>"Mais...", "callback_data"=>"me"))
			),
		),

		"Registro"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"Programador", "callback_data"=>"coder"), array("text"=>"Cyber Sec", "callback_data"=>"sec")),
				array(array("text"=>"Menu", "callback_data"=>"menu"))
			)
		),

		"Ferramentas"=>array(
			"inline_keyboard"=>array(
				array(array("text"=>"Proxy", "callback_data"=>"proxys")),
				array(array("text"=>"Projectos", "callback_data"=>"project"), array("text"=>"wSociety", "callback_data"=>"wsociety")),

			)
		),
	);
}