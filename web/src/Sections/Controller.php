<?php

namespace Natezart\Sections;

abstract class Controller
{
    protected string $_title = 'Natez.art';
    private array $_data = [];

    public function __construct(
        protected readonly string $_layoutFilePath,
        protected string $_viewFilePath
    ) {

    }

    protected function _render(array $vars = []): void
    {
        $this->_data = array_merge($this->_data, $vars);

        require_once $this->_layoutFilePath;
    }

    private function _includeStyles(): void
    {
        $layoutDir = dirname($this->_layoutFilePath);
        $cssFile = $layoutDir . DIRECTORY_SEPARATOR . 'style.css';
        if (file_exists($cssFile)) {
            echo '<style>';
            require_once $cssFile;
            echo '</style>';
        }
    }

    private function _renderView(): void
    {
        extract($this->_data);

        require_once $this->_viewFilePath;
    }
}
