const bt_salvar = document.querySelector("#salvar_contato")
const form = document.querySelector("#contato")

function verify() {
	
	const nome = document.querySelector("#nome")
	const email = document.querySelector("#email")
	const telefone = document.querySelector("#telefone")

	if (nome.value == "" || typeof(nome) == "undefined") {
		alert("Preencha o campo nome corretamente!")
		nome.focus()
		return false
	} 
	else if (email.value != "" && !verifyEmail(email.value.toLowerCase())) {
		alert("E-mail inválido!")
		email.focus()
		return false
	} 
	else if (telefone.value == "" || telefone.value.length < 16) {
		alert("Preencha o campo telefone corretamente!")
		telefone.focus()
		return false
	}

	return true

}

function verifyEmail(email) {
	regex = /^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})$/
	return regex.test(email)
}

function setMask() {
	phone = document.querySelector("#telefone")
	phone.addEventListener('keyup', function() {
		mask(this, mtel)
	})
}

function mask(o, f) {
    v_obj = o
    v_fun = f
    setTimeout("execmask()", 1)
}

function execmask(){
    v_obj.value = v_fun(v_obj.value)
}

function mtel(v){
    v = v.replace(/\D/g, "") //Remove tudo o que não é dígito
    v = v.replace(/^(\d{2})(\d)/g, "($1) $2") //Coloca parênteses em volta dos dois primeiros dígitos
    v = v.replace(/(\d)(\d{4})$/, "$1 - $2") //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}

function edit(id) {

	const url = "ajax_contato.php"
	const params = {
		method: "POST",
		body: JSON.stringify({
			id: id
		})
	}

	fetch(url, params)
		.then(resposta => resposta.json())
		.then(json => {
			if (json.erro == 0) {
				document.querySelector("#add_contact").click()
				document.querySelector("#id_contact").value = json.id
				document.querySelector("#nome").value = json.nome
				document.querySelector("#email").value = json.email
				document.querySelector("#telefone").value = json.telefone
			}
		})

}

function buscar() {

	$.post(
		"busca_contato.php",
		{
			nome: document.querySelector("#buscar").value
		},
		function(data) {
			document.querySelector("#table").innerHTML = data
		}
	);

}

function clean() {

	document.querySelector("#id_contact").value = ""
	document.querySelector("#nome").value = ""
	document.querySelector("#email").value = ""
	document.querySelector("#telefone").value = ""
	
}

window.addEventListener('load', () => {

	bt_salvar.addEventListener('click', function() {
		if(verify()) {
			form.action = "contato_submit.php"
			form.submit()
		}
	})

	setMask()


})