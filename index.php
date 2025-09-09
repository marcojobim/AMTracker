<?php

session_start();

require_once 'config/conexao.php';
require_once 'functions/generos_functions.php';
require_once 'functions/obras_functions.php';
require_once 'functions/upload_functions.php';

$page = $_GET['page'] ?? 'listar_obras';

$actions_sem_view = [
    'salvar_obra',
    'atualizar_obra',
    'excluir_obra'
];

if (in_array($page, $actions_sem_view)) {

    $action_path = "pages/{$page}.php";
    if (file_exists($action_path)) {
        require_once $action_path;
    } else {
        die("Erro 404: Ação '$page' não encontrada.");
    }
} else {
    require_once 'includes/header.php';
    include 'includes/mensagens.php';

    $view_path = "pages/{$page}.php";

    if (file_exists($view_path)) {
        include $view_path;
    } else {
        echo '<main class="content"><h2>Erro 404: Página não encontrada.</h2></main>';
    }

    require_once 'includes/footer.php';
}
