<?php

class Contato {

	private $pdo;
	private $total_contatos;

	public function __construct($pdo) {

		$this->pdo = $pdo;

		$sql = "SELECT COUNT(*) AS total FROM contatos";
		$qry = $pdo->query($sql);
		$total = $qry->fetch();

		$this->total_contatos = $total['total'];

	}

	function getTotalContacts() {
		return $this->total_contatos;
	}

	public function addContact($nome, $email, $telefone) {

		if (!empty($email)) {
			if ($this->verifyEmail($email)) {
				return false;
			}
		}

		if (!$this->verifyTelefone($telefone)) {
			
			$sql  = "INSERT INTO contatos (nome, email, telefone) VALUES (:nome, :email, :telefone)";
			$stmt = $this->pdo->prepare($sql);

			$stmt->bindValue(":nome", $nome);
			$stmt->bindValue(":email", $email);
			$stmt->bindValue(":telefone", $telefone);

			$stmt->execute();	

			return true;

		}

		return false;

	}

	public function getContactById($id) {

		$sql  = "SELECT * FROM contatos WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindValue(":id", $id);

		$stmt->execute();

		return $stmt->rowCount() > 0 ? $stmt->fetch() : array();

	}

	public function getContacts($nome) {

		$sql  = "SELECT * FROM contatos WHERE nome ILIKE '%$nome%' ORDER BY nome ASC";
		$stmt = $this->pdo->query($sql);

		//$stmt->execute(array(":pattern" => "%$nome%"));

		return $stmt->rowCount() > 0 ? $stmt->fetchAll() : array();

	}

	public function getAllContacts($limit, $offset, $nome='') {

		$sql  = "SELECT * FROM contatos LIMIT :limit OFFSET :offset";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindValue(":limit", $limit);
		$stmt->bindValue(":offset", $offset);

		$stmt->execute();

		return $stmt->rowCount() > 0 ? $stmt->fetchAll() : array();

	}

	public function editContact($id, $nome, $email, $telefone) {

		if (!empty($email)) {
			if ($this->verifyEmail($email, $id)) {
				return false;
			}
		}

		if (!$this->verifyTelefone($telefone, $id)) {

			$sql  = "UPDATE contatos SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id";
			$stmt = $this->pdo->prepare($sql);

			$stmt->bindValue(":nome", $nome);
			$stmt->bindValue(":email", $email);
			$stmt->bindValue(":telefone", $telefone);
			$stmt->bindValue(":id", $id);

			$stmt->execute();

			return true;

		}

		return false;

	}

	public function delete($id) {

		$sql  = "DELETE FROM contatos WHERE id = :id";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindValue(":id", $id);

		$stmt->execute();

	}

	public function saveImage($foto, $telefone) {

		$nomedoarquivo = md5(time().rand(0, 99)).'.jpg';

		if (!is_dir("assets/img/contacts/")) {
			mkdir("assets/img/contacts", 0777);
		}
		
		move_uploaded_file($foto['tmp_name'], "assets/img/contacts/$nomedoarquivo");

		$this->manipulatePhoto($nomedoarquivo);

		$sql  = "UPDATE contatos SET foto = :foto WHERE telefone = :telefone";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindValue(":foto", $nomedoarquivo);
		$stmt->bindValue(":telefone", $telefone);

		$stmt->execute();

	}

	public function manipulatePhoto($foto) {

		$filename = $foto;

		$new_width  = 50;
		$new_height = 50;

		list($old_width, $old_heigth) = getimagesize($filename);

		$old_ratio = $old_width / $old_heigth;

		if($new_width / $new_height > $old_ratio) {
			$new_width = $new_height * $old_ratio;
		}
		else {
			$new_height = $new_width / $old_ratio;
		}

		$new_image = imagecreatetruecolor($new_width, $new_height);
		$old_image = imagecreatefromjpeg($filename);
		imagecopyresampled($new_image, $old_image, 0, 0, 0, 0, $new_width, $new_height, $old_width, $old_heigth);
		
		imagejpeg($new_image, $foto, 80);

	}

	public function verifyEmail($email, $id='') {

		$where = (isset($id) && !empty($id)) ? " AND id <> :id" : "";

		$sql  = "SELECT * FROM contatos WHERE email = :email $where";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindValue(":email", $email);

		if (!empty($where)) {
			$stmt->bindValue(":id", $id);
		}

		$stmt->execute();

		return $stmt->rowCount() > 0;

	}

	public function verifyTelefone($telefone, $id='') {

		$where = (isset($id) && !empty($id)) ? " AND id <> :id" : "";

		$sql  = "SELECT * FROM contatos WHERE telefone = :telefone $where";
		$stmt = $this->pdo->prepare($sql);

		$stmt->bindValue(":telefone", $telefone);

		if (!empty($where)) {
			$stmt->bindValue(":id", $id);
		}

		$stmt->execute();

		return $stmt->rowCount() > 0;

	}

}