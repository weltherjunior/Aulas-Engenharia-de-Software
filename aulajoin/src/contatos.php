<?php

/**
 * src/contatos.php
 * Funções de acesso ao banco para contatos e telefones.
 * Cada função recebe um PDO e retorna dados — sem HTML, sem echo.
 */

// ── LISTAGEM ──────────────────────────────────────────────────

/**
 * Retorna todos os contatos com seus telefones agrupados.
 * Usa LEFT JOIN para incluir contatos sem telefone.
 * Agrupa os resultados em PHP: cada contato tem um sub-array "telefones".
 *
 * @return array  [ ['id'=>1,'nome'=>'Maria','email'=>...,'telefones'=>[...]], ... ]
 */
function listarContatosComTelefones(PDO $pdo): array
{
    $sql = "
        SELECT
            c.id          AS contato_id,
            c.nome,
            c.email,
            c.criado_em,
            t.id          AS telefone_id,
            t.tipo,
            t.numero
        FROM contatos c
        LEFT JOIN telefones t ON t.contato_id = c.id
        ORDER BY c.nome, t.tipo
    ";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll();

    // Agrupar: chave = contato_id, valor = dados + array de telefones
    $contatos = [];

    foreach ($rows as $row) {
        $cid = $row['contato_id'];

        // Primeira vez que vemos este contato: cria a entrada
        if (!isset($contatos[$cid])) {
            $contatos[$cid] = [
                'id'        => $cid,
                'nome'      => $row['nome'],
                'email'     => $row['email'],
                'criado_em' => $row['criado_em'],
                'telefones' => [],
            ];
        }

        // LEFT JOIN pode retornar telefone_id = NULL (contato sem telefone)
        if ($row['telefone_id'] !== null) {
            $contatos[$cid]['telefones'][] = [
                'id'     => $row['telefone_id'],
                'tipo'   => $row['tipo'],
                'numero' => $row['numero'],
            ];
        }
    }

    // array_values() reindexar para 0,1,2... (remove chaves com id do contato)
    return array_values($contatos);
}

/**
 * Retorna os dados de um único contato com seus telefones.
 * Retorna null se não encontrado.
 */
function buscarContatoPorId(PDO $pdo, int $id): ?array
{
    $sql = "
        SELECT
            c.id AS contato_id, c.nome, c.email, c.criado_em,
            t.id AS telefone_id, t.tipo, t.numero
        FROM contatos c
        LEFT JOIN telefones t ON t.contato_id = c.id
        WHERE c.id = :id
        ORDER BY t.tipo
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $rows = $stmt->fetchAll();

    if (empty($rows)) {
        return null;
    }

    // Mesmo agrupamento da listagem, mas só para um contato
    $contato = [
        'id'        => $rows[0]['contato_id'],
        'nome'      => $rows[0]['nome'],
        'email'     => $rows[0]['email'],
        'criado_em' => $rows[0]['criado_em'],
        'telefones' => [],
    ];

    foreach ($rows as $row) {
        if ($row['telefone_id'] !== null) {
            $contato['telefones'][] = [
                'id'     => $row['telefone_id'],
                'tipo'   => $row['tipo'],
                'numero' => $row['numero'],
            ];
        }
    }

    return $contato;
}

// ── PAGINAÇÃO ─────────────────────────────────────────────────

/**
 * Retorna contatos paginados (sem telefones — para listagens compactas).
 *
 * @param  int   $pagina     Página atual (começa em 1)
 * @param  int   $porPagina  Itens por página
 * @return array ['contatos' => [...], 'total' => N, 'totalPaginas' => N, 'paginaAtual' => N]
 */
function listarContatosPaginados(PDO $pdo, int $pagina = 1, int $porPagina = 5): array
{
    // Total de contatos
    $total = (int) $pdo->query('SELECT COUNT(*) FROM contatos')->fetchColumn();
    $totalPaginas = (int) ceil($total / $porPagina);

    // Garante que a página está dentro dos limites
    $pagina = max(1, min($pagina, $totalPaginas ?: 1));
    $offset = ($pagina - 1) * $porPagina;

    // Busca a página
    $stmt = $pdo->prepare(
        "SELECT id, nome, email, criado_em
         FROM contatos
         ORDER BY nome
         LIMIT :limite OFFSET :offset"
    );

    // LIMIT e OFFSET precisam de bindValue com PDO::PARAM_INT
    $stmt->bindValue(':limite', $porPagina, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset,    PDO::PARAM_INT);
    $stmt->execute();

    return [
        'contatos'     => $stmt->fetchAll(),
        'total'        => $total,
        'totalPaginas' => $totalPaginas,
        'paginaAtual'  => $pagina,
    ];
}

// ── INSERÇÃO ──────────────────────────────────────────────────

/**
 * Insere um novo contato e seus telefones em transação.
 * Usa PDO::beginTransaction() para garantir atomicidade:
 * se a inserção dos telefones falhar, o contato NÃO é salvo.
 *
 * @param  array  $dados     ['nome' => ..., 'email' => ...]
 * @param  array  $telefones [ ['tipo' => ..., 'numero' => ...], ... ]
 * @return int    ID do contato inserido
 */
function inserirContato(PDO $pdo, array $dados, array $telefones = []): int
{
    $pdo->beginTransaction();

    try {
        // 1. Insere o contato
        $stmt = $pdo->prepare(
            'INSERT INTO contatos (nome, email) VALUES (:nome, :email)'
        );
        $stmt->execute([
            ':nome'  => $dados['nome'],
            ':email' => $dados['email'],
        ]);

        $contatoId = (int) $pdo->lastInsertId();

        // 2. Insere cada telefone (se houver)
        if (!empty($telefones)) {
            $stmtTel = $pdo->prepare(
                'INSERT INTO telefones (contato_id, tipo, numero)
                 VALUES (:contato_id, :tipo, :numero)'
            );

            foreach ($telefones as $tel) {
                if (!empty(trim($tel['numero']))) {
                    $stmtTel->execute([
                        ':contato_id' => $contatoId,
                        ':tipo'       => $tel['tipo']   ?? 'celular',
                        ':numero'     => trim($tel['numero']),
                    ]);
                }
            }
        }

        $pdo->commit(); // tudo certo: confirma
        return $contatoId;

    } catch (Exception $e) {
        $pdo->rollBack(); // algo errado: desfaz tudo
        throw $e;
    }
}

// ── EXCLUSÃO ─────────────────────────────────────────────────

/**
 * Remove um contato. Os telefones são removidos automaticamente
 * graças ao ON DELETE CASCADE definido no banco.
 */
function deletarContato(PDO $pdo, int $id): void
{
    $stmt = $pdo->prepare('DELETE FROM contatos WHERE id = :id');
    $stmt->execute([':id' => $id]);
}
