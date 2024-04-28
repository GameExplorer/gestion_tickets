<?php
// Define upload directory, file type and size restrictions
$targetDir = __DIR__ . "/../adjuntos/";
$fileTypeRestrictions = array("pdf", "png", "jpg", "jpeg");
$maxFileSizeMB = 20; // in megabytes

// Define error messages
$errorMessages = array(
    "invalidFileType" => "Sólo se permite PDF, PNG, JPG, y JPEG.",
    "fileSizeExceedLimit" => "El archivo excede del tamaño máximo."
);