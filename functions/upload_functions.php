<?php

function salvarImagemCapa(array $file): ?string
{
    $uploadDir = __DIR__ . '/../uploads/';

    if (isset($file['error']) && $file['error'] === UPLOAD_ERR_OK) {
        $fileName = uniqid() . '-' . basename($file['name']);
        $uploadFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $uploadFilePath)) {
            return $fileName;
        }
    }
    return null;
}
