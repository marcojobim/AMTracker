<?php

$busca_titulo = $_GET['busca_titulo'] ?? '';
$status_consumo_filtro = $_GET['status_consumo'] ?? '';
$tipo_filtro = $_GET['tipo'] ?? '';
$status_lancamento_filtro = $_GET['status_lancamento'] ?? '';
$genero_filtro = $_GET['genero_id'] ?? '';
$ordenar_por = $_GET['ordenar_por'] ?? '';

$generos_para_filtro = listarGeneros($pdo);

$filtros = [
    'busca_titulo' => $busca_titulo,
    'status_consumo' => $status_consumo_filtro,
    'tipo' => $tipo_filtro,
    'status_lancamento' => $status_lancamento_filtro,
    'genero_id' => $genero_filtro,
    'ordenar_por' => $ordenar_por,
];

$obras = listarObras($pdo, $filtros);
?>

<h2>Meu Catálogo de Obras</h2>

<form method="GET" action="index.php" class="form-filters">
    <input type="hidden" name="page" value="listar_obras">

    <div class="filter-group">
        <label for="busca_titulo">Buscar por Título:</label>
        <input type="search" name="busca_titulo" id="busca_titulo" value="<?= htmlspecialchars($busca_titulo) ?>" placeholder="Nome da obra...">
    </div>

    <div class="filter-group">
        <label for="tipo">Tipo:</label>
        <select name="tipo" id="tipo">
            <option value="">Todos</option>
            <option value="Anime" <?= $tipo_filtro == 'Anime' ? 'selected' : '' ?>>Anime</option>
            <option value="Mangá" <?= $tipo_filtro == 'Mangá' ? 'selected' : '' ?>>Mangá</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="status_lancamento">Status Lançamento:</label>
        <select name="status_lancamento" id="status_lancamento">
            <option value="">Todos</option>
            <option value="Completo" <?= $status_lancamento_filtro == 'Completo' ? 'selected' : '' ?>>Completo</option>
            <option value="Em lançamento" <?= $status_lancamento_filtro == 'Em lançamento' ? 'selected' : '' ?>>Em Lançamento</option>
            <option value="Hiato" <?= $status_lancamento_filtro == 'Hiato' ? 'selected' : '' ?>>Hiato</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="status_consumo">Meu Status:</label>
        <select name="status_consumo" id="status_consumo">
            <option value="">Todos</option>
            <option value="Concluído" <?= $status_consumo_filtro == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
            <option value="Em andamento" <?= $status_consumo_filtro == 'Em andamento' ? 'selected' : '' ?>>Em andamento</option>
            <option value="Interesse" <?= $status_consumo_filtro == 'Interesse' ? 'selected' : '' ?>>Interesse</option>
            <option value="Abandonado" <?= $status_consumo_filtro == 'Abandonado' ? 'selected' : '' ?>>Abandonado</option>
        </select>
    </div>

    <div class="filter-group">
        <label for="genero_id">Genero:</label>
        <select name="genero_id" id="genero_id">
            <option value="">Todos</option>
            <?php foreach ($generos_para_filtro as $g): ?>
                <option value="<?= $g['id'] ?>" <?= $genero_filtro == $g['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($g['nome']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="filter-group">
        <label for="ordenar_por">Ordenar por:</label>
        <select name="ordenar_por" id="ordenar_por">
            <option value="titulo_asc">Título (A-Z)</option>
            <option value="nota_desc" <?= $ordenar_por == 'nota_desc' ? 'selected' : '' ?>>Maior Nota</option>
            <option value="nota_asc" <?= $ordenar_por == 'nota_asc' ? 'selected' : '' ?>>Menor Nota</option>
            <option value="data_fim_desc" <?= $ordenar_por == 'data_fim_desc' ? 'selected' : '' ?>>Concluídos Recentemente</option>
        </select>
    </div>

    <button type="submit" class="btn">Filtrar</button>
</form>

<div class="obras-grid">
    <?php if (count($obras) > 0): ?>
        <?php foreach ($obras as $obra): ?>
            <a href="index.php?page=visualizar_obra&id=<?= $obra['id'] ?>" class="obra-card-link">
                <div class="obra-card">
                    <?php if (!empty($obra['imagem_capa'])): ?>
                        <img src="uploads/<?= htmlspecialchars($obra['imagem_capa']) ?>" alt="Capa de <? htmlspecialchars($obra['titulo']) ?>">
                    <?php else: ?>
                        <img src="assets/images/placeholder.png" alt="Capa não disponivel"> <?php endif; ?>

                    <div class="obra-info">
                        <h3><?= htmlspecialchars($obra['titulo']) ?></h3>

                        <?php $status_class = strtolower(str_replace(' ', '-', $obra['status_consumo'])); ?>
                        <span class="status-badge <?= htmlspecialchars($status_class) ?>">
                            <?= htmlspecialchars($obra['status_consumo']) ?>
                        </span>

                        <p><strong>Tipo:</strong> <?= htmlspecialchars($obra['tipo']) ?></p>

                        <p><strong>Lançamento:</strong> <?= htmlspecialchars($obra['status_lancamento']) ?></p>

                        <?php
                        if ($obra['tipo'] === 'Anime') {
                            if (!empty($obra['total_episodios'])) {
                                echo '<p><strong>Duração:</strong> ' . htmlspecialchars($obra['total_episodios']) . ' episódios</p>';
                            } else {
                                echo '<p><strong>Duração:</strong> Não informada</p>';
                            }
                        } elseif ($obra['tipo'] === 'Mangá') {
                            if (!empty($obra['total_capitulos'])) {
                                echo '<p><strong>Duração:</strong> ' . htmlspecialchars($obra['total_capitulos']) . ' capítulos</p>';
                            } else {
                                echo '<p><strong>Duração:</strong> Não informada</p>';
                            }
                        }
                        ?>

                        <?php if ($obra['nota_pessoal']): ?>
                            <p class="nota"><strong>⭐ Nota:</strong> <?= htmlspecialchars($obra['nota_pessoal']) ?> / 10.0</p>
                        <?php endif; ?>

                        <p><strong>Generos:</strong> <?= htmlspecialchars($obra['generos_nomes'] ?? 'N/A') ?></p>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Nenhuma obra encontrada com os filtros selecionados</p>
    <?php endif; ?>
</div>