<?php

namespace Natezart;

final class Router
{
    public static function getCurrentPath(): string
    {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    public static function isRoutablePath(string $path): bool
    {
        $path = mb_strtoupper($path);
        $path = mb_rtrim($path, '/');
        $pathParts = explode('/', $path);

        // Paths without a file extension in the last part are routable:
        $lastPathPart = array_pop($pathParts);
        return !preg_match('/.+\..+/i', $lastPathPart);
    }

    private static function _getSectionPath(): string
    {
        $url = self::getCurrentPath();

        $logger = new Logger('Router', compact('url'));
        $logger->info('Handle routing');

        $parts = explode('/', mb_rtrim($url, '/'));
        $sectionPart = $parts[0];

        $s = DIRECTORY_SEPARATOR;
        $testPath = __DIR__ . "{$s}src{$s}sections{$s}" . $sectionPart;
        if (!file_exists($testPath)) {
            $sectionPart = '(index)';
        }

        return $sectionPart;
    }

    private static function _getSectionLayoutFile(): string
    {
        $sectionPath = self::_getSectionPath();
        $s = DIRECTORY_SEPARATOR;
        return $GLOBALS['ROOT'] . "{$s}src{$s}sections{$s}" . $sectionPath . "{$s}layout.phtml";
    }

    public static function render(): void
    {
        $s = DIRECTORY_SEPARATOR;
        $url = self::getCurrentPath();

        $sectionPath = self::_getSectionPath();

        $parts = explode('/', mb_rtrim($url, '/'));
        // Remove section part.
        array_shift($parts);

        // Find view file:
        $viewFilePath = $GLOBALS['ROOT'] . "{$s}src{$s}Sections{$s}" . $sectionPath . "{$s}Views";
        $viewFileName = '(index).phtml';

        foreach ($parts as $part) {
            $testPath = $viewFilePath . DIRECTORY_SEPARATOR . $part;
            if (!is_dir($testPath)) {
                $viewFileName = $part . '.phtml';
                break;
            }

            $viewFilePath .= DIRECTORY_SEPARATOR . $part;
        }

        $viewFilePath .= DIRECTORY_SEPARATOR . $viewFileName;

        // Find controller file:
        $controllerFilePath = $GLOBALS['ROOT'] . "{$s}src{$s}Sections{$s}" . $sectionPath . "{$s}Controllers";
        $controllerFileName = '(index).php';
        $controllerFunction = 'index';
        $controllerClass = 'IndexController';

        foreach ($parts as $part) {
            $testPath = $controllerFilePath . DIRECTORY_SEPARATOR . $part;

            if (!is_dir($testPath)) {
                $testPath .= '.php';

                if (is_file($testPath)) {
                    $controllerFileName = $part . '.php';
                    $controllerFunction = 'index';
                    $controllerClass = ucfirst($part) . 'Controller';
                } else {
                    $controllerFunction = $part;
                }
            }
        }

        $controllerFilePath .= DIRECTORY_SEPARATOR . $controllerFileName;

        if (!file_exists($controllerFilePath)) {
            http_response_code(404);
            echo '404'; // TODO: proper 404 page.
            return;
        }

        // Load controller:
        require_once $controllerFilePath;
        $layoutFile = self::_getSectionLayoutFile();
        $controller = new $controllerClass($layoutFile, $viewFilePath);

        if (!method_exists($controller, $controllerFunction)) {
            http_response_code(404);
            echo '404'; // TODO: proper 404 page.
            return;
        }

        $controller->{$controllerFunction}();
    }
}
