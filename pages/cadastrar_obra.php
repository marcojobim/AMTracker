<?php
$generos = listarGeneros($pdo);
?>

<h2>Cadastrar nova Obra</h2>

<form action="index.php?page=salvar_obra" method="POST" enctype="multipart/form-data">

    <div class="form-group">
        <label for="titulo">Título:</label>
        <input type="text" id="titulo" name="titulo" required>
    </div>

    <div class="form-group">
        <label for="tipo">Tipo:</label>
        <select id="tipo" name="tipo" required>
            <option value="Anime">Anime</option>
            <option value="Mangá">Mangá</option>
        </select>
    </div>

    <div class="form-group">
        <label for="status_lancamento">Status de Lançamento:</label>
        <select name="status_lancamento" id="status_lancamento">
            <option value="Completo">Completo</option>
            <option value="Em lançamento">Em Lançamento</option>
            <option value="Hiato">Hiato</option>
        </select>
    </div>

    <div class="form-group">
        <label for="status_consumo">Meu Status de Consumo:</label>
        <select name="status_consumo" id="status_consumo" required>
            <option value="Interesse">Tenho Interesse</option>
            <option value="Em andamento">Em Andamento</option>
            <option value="Concluído">Concluído</option>
            <option value="Abandonado">Abandonado</option>
        </select>
    </div>

    <div id="anime-fields">
        <div class="form-group">
            <label for="total_episodios">Total de Episodios</label>
            <input type="number" id="total_episodios" name="total_episodios" min="0">
        </div>

        <div class="form-group">
            <label for="progresso_episodios">Episodio Atual</label>
            <input type="number" id="progresso_episodios" name="progresso_episodios" min="0">
        </div>
    </div>

    <div id="manga-fields">
        <div class="form-group">
            <label for="total_capitulos">Total de Capitulos</label>
            <input type="number" id="total_capitulos" name="total_capitulos" min="0">
        </div>

        <div class="form-group">
            <label for="progresso_capitulos">Capitulo Atual</label>
            <input type="number" id="progresso_capitulos" name="progresso_capitulos" min="0">
        </div>
    </div>

    <div class="form-group">
        <label for="data_inicio">Data de Início:</label>
        <input type="date" id="data_inicio" name="data_inicio">
    </div>

    <div id="campos_concluido">
        <div class="form-group">
            <label for="data_fim">Data de Fim:</label>
            <input type="date" id="data_fim" name="data_fim">
        </div>

        <div class="form-group">
            <label for="nota_pessoal">Minha Nota (0 a 10):</label>
            <input type="number" id="nota_pessoal" name="nota_pessoal" min="0" max="10" step="0.1">
        </div>

        <div class="form-group">
            <label for="resenha_pessoal">Minha Resenha:</label>
            <textarea name="resenha_pessoal" id="resenha_pessoal" rows="5"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label>Generos:</label>
        <div class="checkbox-group">
            <?php foreach ($generos as $genero): ?>
                <div class="checkbox-item">
                    <input type="checkbox" name="generos[]" id="genero_<?= $genero['id'] ?>" value="<?= $genero['id'] ?>">
                    <label for="genero_<?= $genero['id'] ?>"><?= htmlspecialchars($genero['nome']) ?></label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-group">
        <label for="imagem_capa">Imagem da Capa:</label>
        <input type="file" id="imagem_capa" name="imagem_capa">
    </div>

    <div class="form-group full-width">
        <button type="submit" class="btn">Cadastrar Obra</button>
        <button type="button" onclick="window.location.href='index.php?page=listar_obras'" class="btn btn-secondary">
            Cancelar
        </button>
    </div>

</form>