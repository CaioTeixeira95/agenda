<?php

try {
	$pdo = new PDO("pgsql:dbname=agenda;host=localhost", "caio", "Caio1995");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
	echo "Falha: " . $e->getMessage();
}

$qtd_por_paginas = 6;