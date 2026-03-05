<?php
$target = __DIR__.'/../storage/app/public';
$link = __DIR__.'/storage';
symlink($target, $link);
echo "Enlace simbólico creado con éxito";