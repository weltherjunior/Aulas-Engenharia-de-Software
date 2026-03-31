<?php

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'status' => false,
        'mensagem' => 'Método não permitido.'
    ]);
    exit;
}




$nome           = isset($_POST['nome']) ? trim($_POST['nome']) : '';
$cpf            = isset($_POST['cpf']) ? trim($_POST['cpf']) : '';
$dataNascimento = isset($_POST['data_nascimento']) ? trim($_POST['data_nascimento']) : '';
$telefone       = isset($_POST['telefone']) ? trim($_POST['telefone']) : '';
$email          = isset($_POST['email']) ? trim($_POST['email']) : '';
$logradouro     = isset($_POST['logradouro']) ? trim($_POST['logradouro']) : '';
$cep            = isset($_POST['cep']) ? trim($_POST['cep']) : '';
$bairro         = isset($_POST['bairro']) ? trim($_POST['bairro']) : '';
$cidade         = isset($_POST['cidade']) ? trim($_POST['cidade']) : '';
$estado         = isset($_POST['estado']) ? trim($_POST['estado']) : '';

//validar cpf
function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/', '', $cpf);

    if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        $d = 0;
        for ($c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }

    return true;
}

if(!validarCPF($cpf)) {
    echo json_encode([
        'status' => false,
        'mensagem' => 'CPF inválido.'
    ]);
    exit;
}

//verificar se a data eh valida
if (!DateTime::createFromFormat('Y-m-d', $dataNascimento)) {
    echo json_encode([
        'status' => false,
        'mensagem' => 'Data de nascimento inválida.'
    ]);
    exit;
}

if (
    $nome == '' || $cpf == '' || $dataNascimento == '' || $telefone == '' ||
    $email == '' || $logradouro == '' || $cep == '' || $bairro == '' ||
    $cidade == '' || $estado == ''
) {
    echo json_encode([
        'status' => false,
        'mensagem' => 'Preencha todos os campos obrigatórios.'
    ]);
    exit;
}

// // criar arquivo texto
// $linha  = "----------------------------------------" . PHP_EOL;
// $linha .= "Nome: " . $nome . PHP_EOL;
// $linha .= "CPF: " . $cpf . PHP_EOL;
// $linha .= "Data de Nascimento: " . $dataNascimento . PHP_EOL;
// $linha .= "Telefone: " . $telefone . PHP_EOL;
// $linha .= "E-mail: " . $email . PHP_EOL;
// $linha .= "Logradouro: " . $logradouro . PHP_EOL;
// $linha .= "CEP: " . $cep . PHP_EOL;
// $linha .= "Bairro: " . $bairro . PHP_EOL;
// $linha .= "Cidade: " . $cidade . PHP_EOL;
// $linha .= "Estado: " . $estado . PHP_EOL;
// $linha .= "Data do Cadastro: " . date('d/m/Y H:i:s') . PHP_EOL;

// $arquivo = 'clientes.txt';

// if (file_put_contents($arquivo, $linha, FILE_APPEND) === false) {
    
//     echo json_encode([
//         'status' => false,
//         'mensagem' => 'Erro ao gravar o arquivo TXT.'
//     ]);
//     exit;
// }else{
    

// }

//CRIAR CONEXAO COM O BANCO DE DADOS
$conn = new PDO("mysql:host=localhost;dbname=engsoft;charset=utf8mb4", "root", "");
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $conn->prepare("INSERT INTO cliente (
    nome, cpf, data_nascimento, telefone, email, logradouro, cep, bairro, cidade, estado
) VALUES (
    :nome, :cpf, :data_nascimento, :telefone, :email, :logradouro, :cep, :bairro, :cidade, :estado
)");
$stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
$stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
$stmt->bindValue(':data_nascimento', $dataNascimento, PDO::PARAM_STR);
$stmt->bindValue(':telefone', $telefone, PDO::PARAM_STR);
$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(':logradouro', $logradouro, PDO::PARAM_STR);
$stmt->bindValue(':cep', $cep, PDO::PARAM_STR);
$stmt->bindValue(':bairro', $bairro, PDO::PARAM_STR);
$stmt->bindValue(':cidade', $cidade, PDO::PARAM_STR);
$stmt->bindValue(':estado', $estado, PDO::PARAM_STR);
if ($stmt->execute() === false) {
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro ao salvar no banco de dados: '
    ]); exit;
}else{
echo json_encode([
    'status' => true,
    'mensagem' => 'Cliente cadastrado no Banco de Dados com sucesso.',
     'codigoCliente' => $conn->lastInsertId()
]);
}


