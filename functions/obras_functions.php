<?php

function cadastrarObra(PDO $pdo, array $dadosObra, array $generosIds): bool
{
    $sqlObra = "INSERT INTO obras (titulo, tipo, total_episodios, progresso_episodios, total_capitulos, progresso_capitulos, status_lancamento, imagem_capa,resenha_pessoal, nota_pessoal, status_consumo, data_inicio, data_fim)
    VALUES (:titulo, :tipo, :total_episodios, :progresso_episodios, :total_capitulos, :progresso_capitulos, :status_lancamento, :imagem_capa, :resenha_pessoal, :nota_pessoal, :status_consumo, :data_inicio, :data_fim)";

    $sqlObraGenero = "INSERT INTO genero_obra (obra_id, genero_id) VALUES (?,?)";

    try {
        $pdo->beginTransaction();

        $stmtObra = $pdo->prepare($sqlObra);
        $stmtObra->execute($dadosObra);

        $obraId = $pdo->lastInsertId();

        $stmtObraGenero = $pdo->prepare($sqlObraGenero);
        if (!empty($generosIds)) {
            foreach ($generosIds as $generoId) {
                $stmtObraGenero->execute([$obraId, $generoId]);
            }
        }
        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        return false;
    }
}

function listarObras(PDO $pdo, array $filtros = []): array
{
    $sql = "SELECT obras.*,
                   STRING_AGG(generos.nome, ', ') AS generos_nomes
            FROM obras
            LEFT JOIN genero_obra ON obras.id = genero_obra.obra_id
            LEFT JOIN generos ON genero_obra.genero_id = generos.id";

    $where = [];
    $params = [];

    if (!empty($filtros['busca_titulo'])) {
        $where[] = "obras.titulo ILIKE :titulo";
        $params[':titulo'] = '%' . $filtros['busca_titulo'] . '%';
    }

    if (!empty($filtros['status_consumo'])) {
        $where[] = "obras.status_consumo = :status_consumo";
        $params[':status_consumo'] = $filtros['status_consumo'];
    }

    if (!empty($filtros['tipo'])) {
        $where[] = "obras.tipo = :tipo";
        $params[':tipo'] = $filtros['tipo'];
    }

    if (!empty($filtros['status_lancamento'])) {
        $where[] = "obras.status_lancamento = :status_lancamento";
        $params[':status_lancamento'] = $filtros['status_lancamento'];
    }

    if (!empty($filtros['genero_id'])) {
        $where[] = "obras.id IN (SELECT obra_id FROM genero_obra WHERE genero_id = :genero_id)";
        $params[':genero_id'] = $filtros['genero_id'];
    }

    if (!empty($where)) {
        $sql .= " WHERE " . implode(" AND ", $where);
    }

    $sql .= " GROUP BY obras.id";

    $orderBy = ' ORDER BY obras.titulo ASC';
    if (!empty($filtros['ordenar_por'])) {
        switch ($filtros['ordenar_por']) {
            case 'nota_desc':
                $orderBy = ' ORDER BY obras.nota_pessoal DESC NULLS LAST, obras.titulo ASC';
                break;
            case 'nota_asc':
                $orderBy = ' ORDER BY obras.nota_pessoal ASC NULLS LAST, obras.titulo ASC';
                break;
            case 'data_fim_desc':
                $orderBy = ' ORDER BY obras.data_fim DESC NULLS LAST, obras.titulo ASC';
                break;
        }
    }
    $sql .= $orderBy;

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erro ao listar as obras: " . $e->getMessage());
    }
}

function buscarObraPorId(PDO $pdo, int $id)
{
    $sqlObra = "SELECT
                    obras.*,
                    STRING_AGG(generos.nome, ', ') AS generos_nomes
                FROM obras
                LEFT JOIN genero_obra ON obras.id = genero_obra.obra_id
                LEFT JOIN generos ON genero_obra.genero_id = generos.id
                WHERE obras.id = :id
                GROUP BY obras.id";

    try {
        $stmtObra = $pdo->prepare($sqlObra);
        $stmtObra->execute([':id' => $id]);
        $obra = $stmtObra->fetch(PDO::FETCH_ASSOC);

        if ($obra) {
            $sqlGeneros = "SELECT genero_id FROM genero_obra WHERE obra_id = ?";
            $stmtGeneros = $pdo->prepare($sqlGeneros);
            $stmtGeneros->execute([$id]);

            $generosIds = $stmtGeneros->fetchAll(PDO::FETCH_COLUMN);
            $obra['generos'] = $generosIds;
        }
        return $obra;
    } catch (PDOException $e) {
        die("Erro ao buscar a obra: " . $e->getMessage());
    }
}

function atualizarObra(PDO $pdo, int $id, array $dadosObra, array $generosIds): bool
{
    $dadosObra['id'] = $id;

    $sqlObra = "UPDATE obras SET
                    titulo = :titulo,
                    tipo = :tipo,
                    total_episodios = :total_episodios,
                    progresso_episodios = :progresso_episodios,
                    total_capitulos = :total_capitulos,
                    progresso_capitulos = :progresso_capitulos,
                    status_lancamento = :status_lancamento,
                    imagem_capa = :imagem_capa,
                    resenha_pessoal = :resenha_pessoal,
                    nota_pessoal = :nota_pessoal,
                    status_consumo = :status_consumo,
                    data_inicio = :data_inicio,
                    data_fim = :data_fim
                WHERE id = :id";

    $sqlDeleteGeneros = "DELETE FROM genero_obra WHERE obra_id = ?";
    $sqlInsertGeneros = "INSERT INTO genero_obra (obra_id, genero_id) VALUES (?,?)";

    try {
        $pdo->beginTransaction();

        $stmtObra = $pdo->prepare($sqlObra);
        $stmtObra->execute($dadosObra);

        $stmtDelete = $pdo->prepare($sqlDeleteGeneros);
        $stmtDelete->execute([$id]);

        if (!empty($generosIds)) {
            $stmtInsert = $pdo->prepare($sqlInsertGeneros);
            foreach ($generosIds as $generoId) {
                $stmtInsert->execute([$id, $generoId]);
            }
        }

        $pdo->commit();
        return true;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Erro ao alterar a obra: " . $e->getMessage());
        return false;
    }
}

function excluirObra(PDO $pdo, int $id): bool
{
    try {
        $obra = buscarObraPorId($pdo, $id);
        if ($obra && !empty($obra['imagem_capa'])) {
            $caminhoImagem = __DIR__ . '/../uploads/' . $obra['imagem_capa'];
        } else {
            $caminhoImagem = null;
        }

        $sql = "DELETE FROM obras WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$id]);

        if ($stmt->rowCount() > 0 && $caminhoImagem && file_exists($caminhoImagem)) {
            unlink($caminhoImagem);
        }

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao excluir obra: " . $e->getMessage());
        return false;
    }
}
