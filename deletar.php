<?php

session_start();
include "config.php";
include "classes/Contato.class.php";

if (isset($_GET['id']) && !empty($_GET['id']) && is_numeric($_GET['id'])) {
	
	$id = $_GET['id'];

	$contato = new Contato($pdo);
	$contato->delete($id);

	$_SESSION['msg'] = "Contato deletado com sucesso!";
	$_SESSION['sucesso'] = true;

}

header("Location: index.php");