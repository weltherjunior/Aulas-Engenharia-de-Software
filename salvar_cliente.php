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

// criar arquivo texto
$linha  = "----------------------------------------" . PHP_EOL;
$linha .= "Nome: " . $nome . PHP_EOL;
$linha .= "CPF: " . $cpf . PHP_EOL;
$linha .= "Data de Nascimento: " . $dataNascimento . PHP_EOL;
$linha .= "Telefone: " . $telefone . PHP_EOL;
$linha .= "E-mail: " . $email . PHP_EOL;
$linha .= "Logradouro: " . $logradouro . PHP_EOL;
$linha .= "CEP: " . $cep . PHP_EOL;
$linha .= "Bairro: " . $bairro . PHP_EOL;
$linha .= "Cidade: " . $cidade . PHP_EOL;
$linha .= "Estado: " . $estado . PHP_EOL;
$linha .= "Data do Cadastro: " . date('d/m/Y H:i:s') . PHP_EOL;

$arquivo = 'clientes.txt';

if (file_put_contents($arquivo, $linha, FILE_APPEND) === false) {
    
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro ao gravar o arquivo TXT.'
    ]);
    exit;
}

echo json_encode([
    'status' => true,
    'mensagem' => 'Cliente cadastrado com sucesso.'
]);

/*
// agora vamos salvar no banco de Dados MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cadastro_clientes";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro ao conectar ao banco de dados: ' . $conn->connect_error
    ]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO clientes (nome, cpf, data_nascimento, telefone, email, logradouro, cep, bairro, cidade, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param( $nome, $cpf, $dataNascimento, $telefone, $email, $logradouro, $cep, $bairro, $cidade, $estado);     
if ($stmt->execute() === false) {
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro ao salvar no banco de dados: ' . $stmt->error
    ]);
    exit;
}

echo json_encode([
    'status' => true,
    'mensagem' => 'Cliente cadastrado com sucesso.'
]);
$stmt->close();
$conn->close();
*/