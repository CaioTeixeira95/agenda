<?php

include "config.php";
include "classes/Contato.class.php";

if(isset($_POST['nome']) && !empty($_POST['nome'])) {

	$nome = addslashes($_POST['nome']);

	$contato = new Contato($pdo);

	if (count($contato->getContacts($nome)) == 0) {
		echo "<h3>Nenhum resultado encontrado.</h3>";
	}
	else {
?>
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
				<?php foreach ($contato->getContacts($nome) as $contact): ?>
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
<?php 
	}
}
?>