<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?page=listar_obras');
    exit();
}

$id = (int)($_POST['id'] ?? 0);
$generosIds = $_POST['generos'] ?? [];
$imagem_atual = $_POST['imagem_atual'] ?? null;

if (!$id) {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Erro: ID da obra não fornecido'];
    header('Location: index.php?page=listar_obras');
    exit();
}

$nomeImagem = $imagem_atual;
if (isset($_FILES['imagem_capa']) && $_FILES['imagem_capa']['error'] === UPLOAD_ERR_OK) {
    $nomeImagem = salvarImagemCapa($_FILES['imagem_capa']);

    if ($imagem_atual && $nomeImagem) {
        $caminhoCompleto = __DIR__ . '/../uploads/' . $imagem_atual;
        if (file_exists($caminhoCompleto)) {
            unlink($caminhoCompleto);
        }
    }
}

$dadosObra = [
    'titulo' => $_POST['titulo'] ?? '',
    'tipo' => $_POST['tipo'] ?? '',
    'total_episodios' => !empty($_POST['total_episodios']) ? $_POST['total_episodios'] : null,
    'progresso_episodios' => !empty($_POST['progresso_episodios']) ? $_POST['progresso_episodios'] : null,
    'total_capitulos' => !empty($_POST['total_capitulos']) ? $_POST['total_capitulos'] : null,
    'progresso_capitulos' => !empty($_POST['progresso_capitulos']) ? $_POST['progresso_capitulos'] : null,
    'status_lancamento' => $_POST['status_lancamento'] ?? '',
    'imagem_capa' => $nomeImagem,
    'resenha_pessoal' => !empty($_POST['resenha_pessoal']) ? $_POST['resenha_pessoal'] : null,
    'nota_pessoal' => !empty($_POST['nota_pessoal']) ? $_POST['nota_pessoal'] : null,
    'status_consumo' => $_POST['status_consumo'] ?? '',
    'data_inicio' => !empty($_POST['data_inicio']) ? $_POST['data_inicio'] : null,
    'data_fim' => !empty($_POST['data_fim']) ? $_POST['data_fim'] : null,
];

if (atualizarObra($pdo, $id, $dadosObra, $generosIds)) {
    $_SESSION['mensagem'] = ['tipo' => 'success', 'texto' => 'Obra atualizada com sucesso!'];
} else {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Erro ao atualizar a obra. Tente novamente'];
}

header('Location: index.php?page=listar_obras');
exit();
