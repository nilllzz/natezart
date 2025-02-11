<?php

// Root namespace for all namespaced classes is 'Natezart'.
$GLOBALS['ROOT_NAMESPACE'] = 'Natezart';

spl_autoload_register(callback: function (string $qualifiedClassName): void {
    $namespacedParts = explode('\\', $qualifiedClassName);

    if (count($namespacedParts) > 0 && $namespacedParts[0] === $GLOBALS['ROOT_NAMESPACE']) {
        $namespacedParts[0] = 'src';
    }

    $path = DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $namespacedParts) . '.php';

    require_once $GLOBALS['ROOT'] . $path;
});
