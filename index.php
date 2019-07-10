<?php

session_start();

include "config.php";
include "classes/Contato.class.php";

$contato = new Contato($pdo);

$total = $contato->getTotalContacts();

if(isset($_GET['p']) && is_numeric($_GET['p']) && !empty($_GET['p'])) {
	$pagina_atual = $_GET['p'];
	$p = ($_GET['p'] - 1) * $qtd_por_paginas;
}
else {
	$p = 0;
	$pagina_atual = 1;
}

?>
<!DOCTYPE html>
<html>
<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">

	<title>Agenda</title>
</head>
<body>

	<div class="navbar navbar-dark bg-dark">
		<div class="navbar-brand">
			<img class="d-inline-block align-middle" width="50" heigth="50" src="assets/img/avatar.png" alt="Avatar">
			<span class="align-middle">Agenda de Contato</span>
		</div>
	</div>

	<div class="container">

		<?php if (isset($_SESSION['erro']) && !empty($_SESSION['erro'])): ?>
			<div class="alert alert-danger mt-2">
				<a href="#" class="close" data-dismiss="alert" aria-label="close"><span>&times;</span></a>
				<?=$_SESSION['msg']?>
			</div>
		<?php endif; ?>

		<?php if (isset($_SESSION['sucesso']) && !empty($_SESSION['sucesso'])): ?>
			<div class="alert alert-success mt-2">
				<a href="#" class="close" data-dismiss="alert" aria-label="close"><span>&times;</span></a>
				<?=$_SESSION['msg']?>
			</div>
		<?php 
			endif;
			$_SESSION['erro'] = '';
			$_SESSION['sucesso'] = '';
		?>

		<div class="row mt-2">
			<div class="col-sm mt-2">
				<button id="add_contact" class="btn btn-success" data-toggle="modal" data-target="#form-contact" onclick="clean()"><i class="fa fa-plus"></i> Adicionar Contato</button>
			</div>
			<div id="form-contact" class="modal fade">
				<div class="modal-dialog modal-dialog-centered">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Novo Contato</h4>
							<button class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body">
							<form id="contato" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="id_contact" id="id_contact">
								<div class="form-group input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fa fa-user"></i></div>
									</div>
									<input type="text" class="form-control" placeholder="Nome Completo" name="nome" id="nome">
								</div>
								<div class="form-group input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fa fa-envelope"></i></div>
									</div>
									<input type="email" class="form-control" placeholder="exemple@email.com" name="email" id="email">
								</div>
								<div class="form-group input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fa fa-phone"></i></div>
									</div>
									<input type="text" class="form-control" placeholder="(19) 99999-9999" name="telefone" id="telefone" maxlength="17">
								</div>
								<div class="form-group input-group">
									<div class="input-group-prepend">
										<div class="input-group-text"><i class="fa fa-file-image"></i></div>
									</div>
									<input type="file" class="form-control" name="foto" id="foto" accept="image/png, image/jpeg">
								</div>
							</form>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
        					<button type="button" class="btn btn-primary" name="salvar_contato" id="salvar_contato">Salvar Contato</button>
						</div>	
					</div>
				</div>
			</div>

			<div class="col-sm mt-2">
				<div class="input-group">
					<input type="text" id="buscar" name="buscar" class="form-control" placeholder="Pesquisar contato" aria-label="Input group example" aria-describedby="buscar">
					<div class="input-group-prepend">
						<button class="input-group-text" id="buscar" onclick="buscar();"><i class="fa fa-search"></i></button>
					</div>
				</div>
			</div>

		</div>

		<hr>

		<div class="row">

			<?php if (count($contato->getAllContacts($qtd_por_paginas, $p)) > 0): ?>
				
				<div class="container-fluid" id="table">
					<table class="table table-striped table-responsive-sm">
						<caption>Lista de Contatos</caption>
						<thead class="thead-dark">
							<tr class="text-center">
								<th>Foto</th>
								<th>Nome</th>
								<th>E-mail</th>
								<th>Telefone</th>
								<th colspan="2">Ações</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($contato->getAllContacts($qtd_por_paginas, $p) AS $contact): ?>
								<tr class="text-center">
									<td><img class="rounded-circle" src="assets/img/contacts/<?=$contact['foto']?>" width="50" height="50"></td>
									<td><?=$contact['nome']?></td>
									<td><?=$contact['email']?></td>
									<td><?=$contact['telefone']?></td>
									<td class="text-right">
										<button class="btn btn-primary rounded-circle" id="editar" name="editar" onclick="edit(<?=$contact['id']?>);"><i class="fa fa-pen"></i></button>
									</td>
									<td class="text-left">
										<a href="deletar.php?id=<?=$contact['id']?>" class="btn btn-danger rounded-circle"><i class="fa fa-trash"></i></a>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>

				<div class="container">
					<ul class="pagination justify-content-center">
						<li class="page-item <?=($pagina_atual - 1 < 1) ? 'disabled' : ''?>">
							<a class="page-link" href="./?p=<?=$pagina_atual - 1?>" tabindex="-1">Anterior</a>
						</li>
						<?php
							$proximo = 0;
							for($i = 1; $i <= ceil($total / $qtd_por_paginas); $i++): 
								if ($pagina_atual == $i) {
									$proximo = $i + 1;
								}
						?>
								<li class="page-item <?=($pagina_atual == $i) ? 'active' : ''?>">
									<a class="page-link" href="./?p=<?=$i?>"><?=$i?></a>
								</li>
						<?php endfor; ?>
						<li class="page-item <?=($proximo > ceil($total / $qtd_por_paginas)) ? 'disabled' : ''?>">
							<a class="page-link" href="./?p=<?=$proximo?>">Próximo</a>
						</li>
					</ul>
				</div>

			<?php else: ?>

				<h3>Não há contatos cadastrados</h3>

			<?php endif; ?>

		</div>

	</div>

	<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="assets/js/script.js"></script>
</body>
</html>