<?php

class Paginacao {

	private $pdo;
	private $qtd_por_paginas;

	public function __construct($pdo, $qtd_por_paginas) {
		$this->pdo = $pdo;
		$this->qtd_por_paginas = $qtd_por_paginas;
	}

	

}