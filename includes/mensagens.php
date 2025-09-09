<?php

echo "";
echo "";

if (isset($_SESSION['mensagem']) && !empty($_SESSION['mensagem'])) {

    echo "";

    $msg_texto = $_SESSION['mensagem']['texto'];
    $msg_tipo = $_SESSION['mensagem']['tipo'];

    $class_alert = ($msg_tipo === 'success') ? 'alert-success' : 'alert-danger';

    echo "<div class='alert " . $class_alert . "'>" . htmlspecialchars($msg_texto) . "</div>";

    unset($_SESSION['mensagem']);
} else {
    echo "";
}
