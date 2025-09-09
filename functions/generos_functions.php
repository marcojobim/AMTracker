<?php
function listarGeneros(PDO $pdo):array
{
    $sql = "SELECT * FROM generos ORDER BY nome ASC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}