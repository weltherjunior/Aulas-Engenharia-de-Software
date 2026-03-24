<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Cadastro de Cliente</h3>
                </div>

                <div class="card-body">
                    <div id="mensagem"></div>

                    <form id="formCliente">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome">
                        </div>

                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" name="cpf">
                        </div>

                        <div class="mb-3">
                            <label for="data_nascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="data_nascimento" name="data_nascimento">
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone">
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>

                        <hr>
                        <h5 class="mb-3">Endereço</h5>

                        <div class="mb-3">
                            <label for="logradouro" class="form-label">Logradouro</label>
                            <input type="text" class="form-control" id="logradouro" name="logradouro">
                        </div>

                        <div class="mb-3">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" maxlength="9" onblur="this.value = formatarCEP(this.value)">
                        </div>

                        <div class="mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro">
                        </div>

                        <div class="mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade">
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-select" id="estado" name="estado">
                                <option value="">Selecione o estado</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="btnSalvar">Cadastrar</button>
                            <button type="reset" class="btn btn-secondary">Limpar</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
document.getElementById('formCliente').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const botao = document.getElementById('btnSalvar');
    const mensagem = document.getElementById('mensagem');
    const dados = new FormData(form);

    botao.disabled = true;
    botao.innerText = 'Salvando...';

    //function para validar cpf
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf.length !== 11 || /^(\d)\1+$/.test(cpf)) {
            return false;
        }
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = 11 - (soma % 11);
        if (resto === 10 || resto === 11) {
            resto = 0;
        }
        if (resto !== parseInt(cpf.charAt(9))) {
            return false;
        }
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        if (resto === 10 || resto === 11) {
            resto = 0;
        }
        return resto === parseInt(cpf.charAt(10));
    }

    if(!validarCPF(dados.get('cpf'))) {
        mensagem.innerHTML = '<div class="alert alert-danger">CPF inválido.</div>';
        botao.disabled = false;
        botao.innerText = 'Cadastrar';
        return;
    }

    //fucntion para validar se a data digitada é uma data de nascimento válida
    function validarData(dataString) {
    // 1. Verifica formato com Regex (dd/mm/aaaa)
    const regex = /^\d{2}\/\d{2}\/\d{4}$/;
    if (!regex.test(dataString)) return false;

    // 2. Separa os componentes
    const partes = dataString.split('/');
    const dia = parseInt(partes[0], 10);
    const mes = parseInt(partes[1], 10);
    const ano = parseInt(partes[2], 10);

    // 3. Cria objeto Date e verifica lógica (ex: 30/02)
    const data = new Date(ano, mes - 1, dia);
    return data.getFullYear() === ano &&
           data.getMonth() === mes - 1 &&
           data.getDate() === dia;
}

    if(validarData(dados.get('data_nascimento'))) {
        mensagem.innerHTML = '<div class="alert alert-danger">Data de nascimento inválida. Use o formato dd/mm/aaaa.</div>';
        botao.disabled = false;
        botao.innerText = 'Cadastrar';
        return;
    }


    fetch('salvar_cliente.php', {
        method: 'POST',
        body: dados
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(retorno) {
    
        if (retorno.status) {
            mensagem.innerHTML = '<div class="alert alert-success">' + retorno.mensagem + '</div>';
            form.reset();
            alert("passou");
        } else {
            mensagem.innerHTML = '<div class="alert alert-danger">' + retorno.mensagem + '</div>';
        }
    })
    .catch(function() {
        mensagem.innerHTML = '<div class="alert alert-danger">Erro ao enviar os dados.</div>';
    })
    .finally(function() {
        botao.disabled = false;
        botao.innerText = 'Cadastrar';
    });
});

 //funcao para formatar a mascara do campo cpf
document.getElementById('cpf').addEventListener('input', function(e) {
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 11) valor = valor.slice(0, 11);
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d)/, '$1.$2');
    valor = valor.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    e.target.value = valor;
});
//funcao para formatar a mascara do campo telefone
document.getElementById('telefone').addEventListener('input', function(e) { 
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 11) valor = valor.slice(0, 11);
    valor = valor.replace(/(\d{2})(\d)/, '($1) $2');
    valor = valor.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = valor;
    });

    //funcao para formatar a mascara do campo cep
    document.getElementById('cep').addEventListener('input', function(e) {
    let valor = e.target.value.replace(/\D/g, '');
    if (valor.length > 8) valor = valor.slice(0, 8);
    valor = valor.replace(/(\d{5})(\d)/, '$1-$2');
    e.target.value = valor;
    });


   

</script>

</body>
</html>