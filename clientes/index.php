<?php

$conn = new PDO("mysql:host=localhost;dbname=engsoft;charset=utf8mb4", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$termo = isset($_GET['termo']) ? trim($_GET['termo']) : '';

if ($termo != '') {
    $sql = "SELECT * FROM cliente WHERE nome LIKE :termo OR cpf LIKE :termo ORDER BY nome";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':termo', "%$termo%");
    $stmt->execute();
} else {
    $sql = "SELECT * FROM cliente ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
}

$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Buscar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <h2>Buscar Cliente</h2>

    <form method="get" class="row g-2 mb-3">
        <div class="col-md-10">
            <input type="text" name="termo" class="form-control" placeholder="Digite nome ou CPF" value="<?= htmlspecialchars($termo) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
        <div class="col-md-12">
            <a href="index.php" class="btn btn-secondary w-100">Limpar Busca</a>
        </div>
        <div class="col-md-12 mt-2">
            <a href="cadastrar_cliente.php" class="btn btn-success w-100">Cadastrar Novo Cliente</a>
        </div>
        
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Código</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?= $cliente['id'] ?></td>
                        <td><?= $cliente['nome'] ?></td>
                        <td><?= $cliente['cpf'] ?></td>
                        <td><?= $cliente['telefone'] ?></td>
                        <td>
                            <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="excluir_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>