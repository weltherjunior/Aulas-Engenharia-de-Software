<?php
// ============================================================
// api/pacientes.php — Endpoint da API REST de Pacientes
// Cada método HTTP chama uma função dedicada
// ============================================================

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

require_once "../conexao.php";

// ─────────────────────────────────────────────────────────────
// FUNÇÕES — uma para cada operação
// ─────────────────────────────────────────────────────────────

// GET — lista todos os pacientes
function listarPacientes(PDO $conn): void
{
    $stmt = $conn->prepare("SELECT * FROM pacientes ORDER BY nome ASC");
    $stmt->execute();
    $pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "data"   => $pacientes,
        "total"  => count($pacientes)
    ], JSON_UNESCAPED_UNICODE);
}

// GET — busca um paciente pelo id
function buscarPaciente(PDO $conn, int $id): void
{
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["status"=>"error","message"=>"ID invalido."], JSON_UNESCAPED_UNICODE);
        return;
    }

    $stmt = $conn->prepare("SELECT * FROM pacientes WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$paciente) {
        http_response_code(404);
        echo json_encode(["status"=>"error","message"=>"Paciente nao encontrado."], JSON_UNESCAPED_UNICODE);
        return;
    }

    echo json_encode(["status"=>"success","data"=>$paciente], JSON_UNESCAPED_UNICODE);
}

// POST — cadastra um novo paciente
function cadastrarPaciente(PDO $conn): void
{
    // Lê e decodifica o JSON do corpo da requisição
    $body = json_decode(file_get_contents("php://input"), true);

    if (!$body) {
        http_response_code(400);
        echo json_encode(["status"=>"error","message"=>"Corpo da requisicao invalido."], JSON_UNESCAPED_UNICODE);
        return;
    }

    // Extrai os campos
    $nome            = trim($body["nome"]            ?? "");
    $cpf             = trim($body["cpf"]             ?? "");
    $data_nascimento = trim($body["data_nascimento"] ?? "");
    $telefone        = !empty($body["telefone"]) ? trim($body["telefone"]) : null;
    $convenio        = !empty($body["convenio"])  ? trim($body["convenio"])  : null;

    // Valida campos obrigatórios
    $erros = [];
    if (empty($nome))            $erros[] = "nome";
    if (empty($cpf))             $erros[] = "cpf";
    if (empty($data_nascimento)) $erros[] = "data_nascimento";

    if (!empty($erros)) {
        http_response_code(400);
        echo json_encode([
            "status"  => "error",
            "message" => "Campos obrigatorios ausentes.",
            "campos"  => $erros
        ], JSON_UNESCAPED_UNICODE);
        return;
    }

    // Executa o INSERT
    try {
        $sql = "INSERT INTO pacientes (nome, cpf, data_nascimento, telefone, convenio)
                VALUES (:nome, :cpf, :data_nascimento, :telefone, :convenio)";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(":nome",            $nome);
        $stmt->bindValue(":cpf",             $cpf);
        $stmt->bindValue(":data_nascimento", $data_nascimento);
        $stmt->bindValue(":telefone",        $telefone, $telefone ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->bindValue(":convenio",        $convenio, $convenio ? PDO::PARAM_STR : PDO::PARAM_NULL);
        $stmt->execute();

        http_response_code(201);
        echo json_encode([
            "status"  => "success",
            "message" => "Paciente cadastrado com sucesso.",
            "id"      => intval($conn->lastInsertId())
        ], JSON_UNESCAPED_UNICODE);

    } catch (PDOException $e) {
        // Código 23000 = violação de chave única (CPF duplicado)
        if ($e->getCode() === '23000') {
            http_response_code(409);
            echo json_encode(["status"=>"error","message"=>"CPF ja cadastrado no sistema."], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(["status"=>"error","message"=>"Erro interno ao salvar."], JSON_UNESCAPED_UNICODE);
        }
    }
}

// DELETE — exclui um paciente pelo id
function excluirPaciente(PDO $conn, int $id): void
{
    if ($id <= 0) {
        http_response_code(400);
        echo json_encode(["status"=>"error","message"=>"ID invalido ou ausente."], JSON_UNESCAPED_UNICODE);
        return;
    }

    // Verifica se o paciente existe antes de deletar
    $stmt = $conn->prepare("SELECT id FROM pacientes WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(["status"=>"error","message"=>"Paciente nao encontrado."], JSON_UNESCAPED_UNICODE);
        return;
    }

    // Executa o DELETE
    $stmt = $conn->prepare("DELETE FROM pacientes WHERE id = :id");
    $stmt->bindValue(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        "status"  => "success",
        "message" => "Paciente excluido com sucesso."
    ], JSON_UNESCAPED_UNICODE);
}

// ─────────────────────────────────────────────────────────────
// ROTEADOR — identifica o método e chama a função correta
// ─────────────────────────────────────────────────────────────

$metodo = $_SERVER["REQUEST_METHOD"];
$id     = intval($_GET["id"] ?? 0);

if ($metodo === "GET") {

    // Se id foi informado na URL, busca apenas aquele paciente
    if (isset($_GET["id"])) {
        buscarPaciente($conn, $id);
    } else {
        listarPacientes($conn);
    }

} elseif ($metodo === "POST") {

    cadastrarPaciente($conn);

} elseif ($metodo === "DELETE") {

    excluirPaciente($conn, $id);

} else {
    http_response_code(405);
    echo json_encode([
        "status"  => "error",
        "message" => "Metodo HTTP nao permitido. Use GET, POST ou DELETE."
    ], JSON_UNESCAPED_UNICODE);
}
