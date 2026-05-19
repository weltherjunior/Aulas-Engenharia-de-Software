<?php


$conn = new PDO("mysql:host=localhost;dbname=engsoft;charset=utf8mb4", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arquivo = $_FILES['file'];
     

    if($arquivo['error'] === UPLOAD_ERR_OK) {
        $nomeArquivo = uniqid() . '_' . basename($arquivo['name']);
        $destino = 'uploads/' . $nomeArquivo;
        //cadastra a pessoa no banco de dados
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $telefone = $_POST['telefone'];
        $stmt = $conn->prepare("INSERT INTO pessoas (nome, email, telefone) VALUES (:nome, :email, :telefone)");
        $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);
        $stmt->execute();
        //recuperar o id da pessoa cadastrada
        $idPessoa = $conn->lastInsertId();


        if(move_uploaded_file($arquivo['tmp_name'], $destino)) {
            echo "Arquivo enviado com sucesso: " . $nomeArquivo;
            $stmt = $conn->prepare("INSERT INTO arquivos (nome_arquivo, id_pessoa) VALUES (:nome_arquivo, :id_pessoa)");
            $stmt->bindValue(':nome_arquivo', $nomeArquivo, PDO::PARAM_STR);
            $stmt->bindValue(':id_pessoa', $idPessoa, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            echo "Erro ao salvar o arquivo.";
        }
    } else {
        echo "Erro no upload: " . $arquivo['error'];
    }
} else {
    echo "Nenhum arquivo enviado.";
}


//listar arquivos
$stmt = $conn->query("SELECT id, nome_arquivo FROM arquivos");  
$arquivos = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($arquivos as $arquivo) {
    echo "<p><a href='uploads/" . $arquivo['nome_arquivo'] . "'>" . $arquivo['nome_arquivo'] . "</a></p>";
}



?>


