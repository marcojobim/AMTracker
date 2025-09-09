<?php

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    die("Erro: ID da obra não fornecido");
}

$obra = buscarObraPorId($pdo, $id);

if (!$obra) {
    die("Erro: Obra não encontrada");
}

$todosOsGeneros = listarGeneros($pdo);
?>

<h2>Editar Obra: <?= htmlspecialchars($obra['titulo']) ?></h2>

<form action="index.php?page=atualizar_obra" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $obra['id'] ?>">

    <div class="form-grid">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($obra['titulo']) ?>" required>
        </div>

        <div class="form-group">
            <label for="tipo">Tipo:</label>
            <select id="tipo" name="tipo" required>
                <option value="Anime" <?= $obra['tipo'] == 'Anime' ? 'selected' : '' ?>>Anime</option>
                <option value="Mangá" <?= $obra['tipo'] == 'Mangá' ? 'selected' : '' ?>>Mangá</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status_lancamento">Status de Lançamento:</label>
            <select name="status_lancamento" id="status_lancamento">
                <option value="Completo" <?= $obra['status_lancamento'] == 'Completo' ? 'selected' : '' ?>>Completo</option>
                <option value="Em lançamento" <?= $obra['status_lancamento'] == 'Em lançamento' ? 'selected' : '' ?>>Em Lançamento</option>
                <option value="Hiato" <?= $obra['status_lancamento'] == 'Hiato' ? 'selected' : '' ?>>Hiato</option>
            </select>
        </div>

        <div class="form-group">
            <label for="status_consumo">Meu Status de Consumo:</label>
            <select id="status_consumo" name="status_consumo" required>
                <option value="Interesse" <?= $obra['status_consumo'] == 'Interesse' ? 'selected' : '' ?>>Interesse</option>
                <option value="Em andamento" <?= $obra['status_consumo'] == 'Em andamento' ? 'selected' : '' ?>>Em Andamento</option>
                <option value="Concluído" <?= $obra['status_consumo'] == 'Concluído' ? 'selected' : '' ?>>Concluído</option>
                <option value="Abandonado" <?= $obra['status_consumo'] == 'Abandonado' ? 'selected' : '' ?>>Abandonado</option>
            </select>
        </div>

        <div id="anime-fields">
            <div class="form-group">
                <label for="total_episodios">Total de Episódios:</label>
                <input type="number" id="total_episodios" name="total_episodios" min="0" value="<?= htmlspecialchars($obra['total_episodios']) ?>">
            </div>

            <div class="form-group">
                <label for="progresso_episodios">Episódios Assistidos:</label>
                <input type="number" id="progresso_episodios" name="progresso_episodios" min="0" value="<?= htmlspecialchars($obra['progresso_episodios']) ?>">
            </div>
        </div>

        <div id="manga-fields">
            <div class="form-group">
                <label for="total_capitulos">Total de Capítulos:</label>
                <input type="number" id="total_capitulos" name="total_capitulos" min="0" value="<?= htmlspecialchars($obra['total_capitulos']) ?>">
            </div>

            <div class="form-group">
                <label for="progresso_capitulos">Capítulos Lidos:</label>
                <input type="number" id="progresso_capitulos" name="progresso_capitulos" min="0" value="<?= htmlspecialchars($obra['progresso_capitulos']) ?>">
            </div>
        </div>

        <div class="form-group">
            <label for="data_inicio">Data de Início:</label>
            <input type="date" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($obra['data_inicio']) ?>">
        </div>

        <div id="campos_concluido" class="full-width">
            <div class="form-grid">

                <div id="campos_concluido">
                    <div class="form-group">
                        <label for="data_fim">Data de Término:</label>
                        <input type="date" id="data_fim" name="data_fim" value="<?= htmlspecialchars($obra['data_fim']) ?>">
                    </div>

                    <div class="form-group">
                        <label for="nota_pessoal">Minha Nota (0 a 10):</label>
                        <input type="number" id="nota_pessoal" name="nota_pessoal" min="0" max="10" step="0.1" value="<?= htmlspecialchars($obra['nota_pessoal']) ?>">
                    </div>

                    <div class="form-group full-width">
                        <label for="resenha_pessoal">Minha Resenha:</label>
                        <textarea name="resenha_pessoal" id="resenha_pessoal" rows="5"><?= htmlspecialchars($obra['resenha_pessoal'] ?? 'Nenhuma resenha escrita') ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label>Generos:</label>
            <div class="checkbox-group">
                <?php foreach ($todosOsGeneros as $genero): ?>
                    <?php
                    $checked = in_array($genero['id'], $obra['generos']) ? 'checked' : '';
                    ?>
                    <div class="checkbox-item">
                        <input type="checkbox" name="generos[]" id="genero_<?= $genero['id'] ?>" value="<?= $genero['id'] ?>" <?= $checked ?>>
                        <label for="genero_<?= $genero['id'] ?>"><?= htmlspecialchars($genero['nome']) ?></label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-group full-width">
            <label for="imagem_capa">Alterar Imagem da Capa:</label>
            <input type="file" id="imagem_capa" name="imagem_capa">
            <?php if (!empty($obra['imagem_capa'])): ?>
                <p>Imagem atual:</p>
                <img src="uploads/<?= htmlspecialchars($obra['imagem_capa']) ?>" alt="Capa Atual" width="100">
                <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($obra['imagem_capa']) ?>">
            <?php endif; ?>
        </div>

        <div class="form-group full-width">
            <button type="submit" class="btn">Atualizar Obra</button>
            <button type="button" onclick="window.location.href='index.php?page=listar_obras'" class="btn btn-secondary">
                Cancelar
            </button>
        </div>
    </div>
</form>