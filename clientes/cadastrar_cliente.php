<?php
$conn = new PDO("mysql:host=localhost;dbname=engsoft;charset=utf8mb4", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $data_nascimento = trim($_POST['data_nascimento']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $logradouro = trim($_POST['logradouro']);
    $cep = trim($_POST['cep']);
    $bairro = trim($_POST['bairro']);
    $cidade = trim($_POST['cidade']);
    $estado = trim($_POST['estado']);

    if ($nome == "" || $cpf == "") {
        $mensagem = "<div class='alert alert-danger'>Nome e CPF são obrigatórios.</div>";
    } else {
        $sql = "INSERT INTO cliente 
                    (nome, cpf, data_nascimento, telefone, email, logradouro, cep, bairro, cidade, estado)
                VALUES
                    (:nome, :cpf, :data_nascimento, :telefone, :email, :logradouro, :cep, :bairro, :cidade, :estado)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', $nome);
        $stmt->bindValue(':cpf', $cpf);
        $stmt->bindValue(':data_nascimento', $data_nascimento);
        $stmt->bindValue(':telefone', $telefone);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':logradouro', $logradouro);
        $stmt->bindValue(':cep', $cep);
        $stmt->bindValue(':bairro', $bairro);
        $stmt->bindValue(':cidade', $cidade);
        $stmt->bindValue(':estado', $estado);

        if ($stmt->execute()) {
            $codigo = $conn->lastInsertId();
             echo "<script>alert('Cliente cadastrado com sucesso! Código: $codigo'); window.location.href = 'index.php';</script>";  
        } else {
            $mensagem = "<div class='alert alert-danger'>Erro ao cadastrar cliente.</div>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Cadastrar Cliente</h2>

    <?= $mensagem ?>

    <form method="post">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nome</label>
                <input type="text" id="nome" name="nome" class="form-control" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">CPF</label>
                <input type="text" id="cpf" name="cpf" class="form-control" required>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Telefone</label>
                <input type="text" id="telefone" name="telefone" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" id="email" name="email" class="form-control">
            </div>
        </div>

        <hr>
        <h5>Endereço</h5>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Logradouro</label>
                <input type="text" id="logradouro" name="logradouro" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">CEP</label>
                <input type="text" id="cep" name="cep" class="form-control">
            </div>

            <div class="col-md-3 mb-3">
                <label class="form-label">Bairro</label>
                <input type="text" id="bairro" name="bairro" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Cidade</label>
                <input type="text" name="cidade" class="form-control">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Selecione</option>
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
        </div>

        <button type="submit" class="btn btn-primary">Cadastrar</button>
        <a href="index.php" class="btn btn-secondary">Buscar Cliente</a>
    </form>
</div>
</body>

<script>
  //mascara do campo cpf
    document.getElementById('cpf').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        this.value = value;
    });

    //mascara do campo telefone
    document.getElementById('telefone').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 11) {
            value = value.slice(0, 11);
        }
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        this.value = value;
    });

    //mascara do campo CEP
    document.getElementById('cep').addEventListener('input', function(e) {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 8) {
            value = value.slice(0, 8);
        }
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        this.value = value;
    });


</script>
</html>