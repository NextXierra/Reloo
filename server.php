<?php

/**
 * Reloo - Router Script for PHP Built-in Server & Wasmer Edge
 *
 * Emulates Apache mod_rewrite / Nginx try_files $uri $uri/ /index.php?$query_string
 * for both local execution and Wasmer WASI WebAssembly runtime.
 */

$publicPath = __DIR__ . '/public';

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/'
);

// If the requested URI exists as a physical static file in public/, serve it directly
if ($uri !== '/' && file_exists($publicPath . $uri) && !is_dir($publicPath . $uri)) {
    return false;
}

require_once $publicPath . '/index.php';
