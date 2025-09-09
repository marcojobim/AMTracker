<?php

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Acesso Inválido'];
    header('Location: index.php?page=cadastrar_obra');
    exit();
}

$nomeImagem = salvarImagemCapa($_FILES['imagem_capa']);

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
$generosIds = $_POST['generos'] ?? [];

if (empty($dadosObra['titulo']) || empty($dadosObra['tipo']) || empty($dadosObra['status_consumo'])) {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Por favor preencha os campos obrigatórios'];
    header('Location: index.php?page=cadastrar_obra');
    exit();
}

if (cadastrarObra($pdo, $dadosObra, $generosIds)) {
    $_SESSION['mensagem'] = ['tipo' => 'success', 'texto' => 'Obra cadastrada com sucesso!'];
    header('Location: index.php?page=listar_obras');
} else {
    $_SESSION['mensagem'] = ['tipo' => 'danger', 'texto' => 'Erro ao cadastrar a obra. Tente novamente'];
    header('Location: index.php?page=cadastrar_obra');
}
exit();
