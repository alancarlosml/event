<?php
// Arquivo temporário para limpar OPcache - DELETAR após uso
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo 'OPcache resetado com sucesso!';
} else {
    echo 'OPcache não está ativo.';
}
