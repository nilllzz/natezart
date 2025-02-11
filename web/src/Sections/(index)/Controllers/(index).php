<?php

use Natezart\Sections\Controller;

final class IndexController extends Controller
{
    public function index(): void
    {
        $this->_title = 'INDEX TITLE';
        $this->_render(['test' => 'Hello, World!']);
    }

    public function about(): void
    {
        $this->_title = 'ABOUT';
        $this->_render();
    }
}
