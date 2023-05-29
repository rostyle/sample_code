<?php
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
    $host = $_SERVER['HTTP_HOST'];
    $directory = str_replace("\\", "/", dirname(__DIR__));
    $current_directory_url = $protocol . "://" . $host . str_replace($_SERVER['DOCUMENT_ROOT'], '', $directory);