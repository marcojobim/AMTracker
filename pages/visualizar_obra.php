<?php

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    die("Erro: ID da obra não fornecido");
}

$obra = buscarObraPorId($pdo, $id);

if (!$obra) {
    die("Erro: Obra não encontrada");
}
?>

<div class="view-obra-container">
    <div class="view-obra-header">
        <h1><?= htmlspecialchars($obra['titulo']) ?></h1>
        <div class="view-obra-actions">
            <a href="index.php?page=editar_obra&id=<?= $obra['id'] ?>" class="btn-edit">Editar</a>
            <a href="index.php?page=excluir_obra&id=<?= $obra['id'] ?>" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir esta obra?');">Excluir</a>
        </div>
    </div>

    <div class="view-obra-content">
        <div class="view-obra-image">
            <?php if (!empty($obra['imagem_capa'])): ?>
                <img src="uploads/<?= htmlspecialchars($obra['imagem_capa']) ?>" alt="Capa de <?= htmlspecialchars($obra['titulo']) ?>">
            <?php else: ?>
                <img src="assets/images/placeholder.png" alt="Capa não disponível">
            <?php endif; ?>
        </div>

        <div class="view-obra-details">
            <h2>Detalhes</h2>
            <p><strong>Tipo:</strong> <?= htmlspecialchars($obra['tipo']) ?></p>
            <p><strong>Status de Lançamento:</strong> <?= htmlspecialchars($obra['status_lancamento']) ?></p>

            <?php
            if ($obra['tipo'] === 'Anime') {
                if (!empty($obra['total_episodios'])) {
                    echo '<p><strong>Duração:</strong> ' . htmlspecialchars($obra['total_episodios']) . ' episódios</p>';
                } else {
                    echo '<p><strong>Duração:</strong> Não informada</p>';
                }

                echo '<p><strong>Meu Progresso: </strong>' . htmlspecialchars($obra['progresso_episodios'] ?? '0') . ' assistidos</p>';
            } elseif ($obra['tipo'] === 'Mangá') {
                if (!empty($obra['total_capitulos'])) {
                    echo '<p><strong>Duração:</strong> ' . htmlspecialchars($obra['total_capitulos']) . ' capítulos</p>';
                } else {
                    echo '<p><strong>Duração:</strong> Não informada</p>';
                }

                echo '<p><strong>Meu Progresso: </strong>' . htmlspecialchars($obra['progresso_capitulos'] ?? '0') . ' lidos</p>';
            }
            ?>

            <p><strong>Generos:</strong> <?= htmlspecialchars($obra['generos_nomes'] ?? 'Nenhum genero associado.') ?></p>
        </div>
    </div>

    <div class="view-obra-personal">
        <h2>Minha avaliação</h2>
        <p><strong>Meu Status:</strong> <?= htmlspecialchars($obra['status_consumo']) ?></p>
        <?php if ($obra['nota_pessoal']): ?>
            <p class="nota"><strong>Minha Nota:</strong> ⭐ <?= htmlspecialchars($obra['nota_pessoal']) ?> / 10.0</p>
        <?php endif; ?>

        <?php if ($obra['data_inicio']): ?>
            <p><strong>Comecei em:</strong> <?= date('d/m/Y', strtotime($obra['data_inicio'])) ?></p>
        <?php endif; ?>

        <?php if ($obra['data_fim']): ?>
            <p><strong>Terminei em:</strong> <?= date('d/m/Y', strtotime($obra['data_fim'])) ?></p>
        <?php endif; ?>

        <div class="resenha">
            <h3>Minha Resenha</h3>
            <p><?= nl2br(htmlspecialchars($obra['resenha_pessoal'] ?? 'Nenhuma resenha escrita.')) ?></p>
        </div>
    </div>
</div>