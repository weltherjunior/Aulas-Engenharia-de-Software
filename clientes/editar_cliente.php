<?php
$conn = new PDO("mysql:host=localhost;dbname=engsoft;charset=utf8mb4", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM cliente WHERE id = :id");
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cliente) {
    die("Cliente não encontrado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $cpf = $_POST['cpf'];
    $data_nascimento = $_POST['data_nascimento'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $logradouro = $_POST['logradouro'];
    $cep = $_POST['cep'];
    $bairro = $_POST['bairro'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $id = $_POST['id'];

    $sql = "UPDATE cliente SET 
                nome = :nome,
                cpf = :cpf,
                data_nascimento = :data_nascimento,
                telefone = :telefone,
                email = :email,
                logradouro = :logradouro,
                cep = :cep,
                bairro = :bairro,
                cidade = :cidade,
                estado = :estado
            WHERE id = :id";

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
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "<script>alert('Cliente atualizado com sucesso!'); window.location='index.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger'>Erro ao atualizar.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Editar Cliente</h2>

    <form method="post">
            <input type="hidden" name="id" value="<?= $cliente['id'] ?>">           
        <div class="mb-3">
            <label>Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" value="<?= htmlspecialchars($cliente['nome']) ?>">
        </div>

        <div class="mb-3">
            <label>CPF</label>
            <input type="text" id="cpf" name="cpf" class="form-control" value="<?= htmlspecialchars($cliente['cpf']) ?>">
        </div>

        <div class="mb-3">
            <label>Data de Nascimento</label>
            <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="<?= $cliente['data_nascimento'] ?>">
        </div>

        <div class="mb-3">
            <label>Telefone</label>
            <input type="text" id="telefone" name="telefone" class="form-control" value="<?= htmlspecialchars($cliente['telefone']) ?>">
        </div>

        <div class="mb-3">
            <label>E-mail</label>
            <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($cliente['email']) ?>">
        </div>

        <div class="mb-3">
            <label>Logradouro</label>
            <input type="text" id="logradouro" name="logradouro" class="form-control" value="<?= htmlspecialchars($cliente['logradouro']) ?>">
        </div>

        <div class="mb-3">
            <label>CEP</label>
            <input type="text" id="cep"    name="cep" class="form-control" value="<?= htmlspecialchars($cliente['cep']) ?>">
        </div>

        <div class="mb-3">
            <label>Bairro</label>
            <input type="text" id="bairro"   name="bairro" class="form-control" value="<?= htmlspecialchars($cliente['bairro']) ?>">
        </div>

        <div class="mb-3">
            <label>Cidade</label>
            <input type="text" id="cidade" name="cidade" class="form-control" value="<?= htmlspecialchars($cliente['cidade']) ?>">
        </div>

        <div class="mb-3">
            <label>Estado</label>
            <select id="estado"  name="estado" class="form-select">
                <option value="GO" <?= $cliente['estado'] == 'GO' ? 'selected' : '' ?>>Goiás</option>
                <option value="SP" <?= $cliente['estado'] == 'SP' ? 'selected' : '' ?>>São Paulo</option>
                <option value="MG" <?= $cliente['estado'] == 'MG' ? 'selected' : '' ?>>Minas Gerais</option>
                <option value="RJ" <?= $cliente['estado'] == 'RJ' ? 'selected' : '' ?>>Rio de Janeiro</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Atualizar</button>
        <a href="index.php" class="btn btn-secondary">Voltar</a>
    </form>
</div>
</body>

<script>
    // Máscara para CPF
    document.getElementById('cpf').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });

    // Máscara para telefone
    document.getElementById('telefone').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 11) value = value.slice(0, 11);
        if (value.length > 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        }
        e.target.value = value;
    });

    // Máscara para CEP
    document.getElementById('cep').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length > 8) value = value.slice(0, 8);
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });
</script>
</html>