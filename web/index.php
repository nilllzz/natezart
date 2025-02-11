<?php

use Natezart\Router;

$GLOBALS['ROOT'] = __DIR__;

// Register autoloader:
require_once $GLOBALS['ROOT'] . '/autoload.php';

final class Program
{
	public function main(): void
	{
		if (!Router::isRoutablePath(Router::getCurrentPath())) {
			http_response_code(404);
			echo '404'; // TODO: proper 404 page.
			return;
		}

		Router::render();
	}
}

$program = new Program();
$program->main();
