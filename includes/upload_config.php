<?php
// Define upload directory, file type and size restrictions
//WARNING: If you change the targetDir, it won't move files to the new folder.
//Also make sure the folder exists and have all permissions. Default one is $targetDir = "adjuntos/";
$targetDir = "adjuntos/";
$fileTypeRestrictions = array("pdf", "png", "jpg", "jpeg");
$maxFileSizeMB = 20; // in megabytes

// Define error messages
$errorMessages = array(
    "invalidFileType" => "Sólo se permite PDF, PNG, JPG, y JPEG.",
    "fileSizeExceedLimit" => "El archivo excede del tamaño máximo."
);