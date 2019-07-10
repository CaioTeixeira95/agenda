<?php

include "config.php";
include "classes/Contato.class.php";

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id']) && !empty($data['id']) && is_numeric($data['id'])) {
	
	$id = $data['id'];

	$contato = new Contato($pdo);

	$contact = $contato->getContactById($id);

	echo json_encode(
		array(
			"erro" => 0,
			"id" => $contact['id'],
			"nome" => $contact['nome'],
			"email" => $contact['email'],
			"telefone" => $contact['telefone']
		)
	);

}
else {
	echo json_encode(array("erro" => 1));
}