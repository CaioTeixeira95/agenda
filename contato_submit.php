<?php

session_start();
include "config.php";
include "classes/Contato.class.php";

$id       = $_POST['id_contact'];
$nome     = addslashes($_POST['nome']);
$email    = addslashes($_POST['email']);
$telefone = addslashes($_POST['telefone']);
$foto     = $_FILES['foto'];

if (!empty($nome) && !empty($telefone)) {

	$contato = new Contato($pdo);

	if (empty($id)) {
		if($contato->addContact($nome, $email, $telefone)) {
			if (isset($foto['tmp_name']) && !empty($foto['tmp_name'])) {
				$contato->saveImage($foto, $telefone);
			}
			$_SESSION['msg'] = "Contato salvo com sucesso!";
			$_SESSION['sucesso'] = true;
		}
		else {
			$_SESSION['msg'] = "Não foi possível salvar o contato!";
			$_SESSION['erro'] = true;
		}
	}
	else {
		if($contato->editContact($id, $nome, $email, $telefone)) {
			if (isset($foto['tmp_name']) && !empty($foto['tmp_name'])) {
				$contato->saveImage($foto, $telefone);
			}
			$_SESSION['msg'] = "Contato alterado com sucesso!";
			$_SESSION['sucesso'] = true;
		}
		else {
			$_SESSION['msg'] = "Não foi possível alterar o contato!";
			$_SESSION['erro'] = true;
		}
	}

}

header("Location: index.php");