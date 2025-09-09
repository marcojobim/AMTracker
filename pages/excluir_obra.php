<?php

$id = (int)($_GET['id'] ?? 0);

if (!$id) {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Erro: ID da obra não fornecido'];
    header('Location: index.php?page=listar_obras');
    exit();
}

if (excluirObra($pdo, $id)) {
    $_SESSION['mensagem'] = ['tipo' => 'success', 'texto' => 'Obra excluída com sucesso!'];
} else {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Erro ao excluir a obra'];
}

header('Location: index.php?page=listar_obras');
exit();
