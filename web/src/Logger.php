<?php

namespace Natezart;

final class Logger
{
    private static ?Logger $_globalLogger = null;

    public static function global(): self
    {
        if (self::$_globalLogger === null) {
            self::$_globalLogger = new self('GLOBAL');
        }
        return self::$_globalLogger;
    }

    public readonly string $context;
    private array $_data = [];

    public function __construct(string $context, ?array $data = [])
    {
        $this->context = $context;
        $this->_data = $data;
    }

    private function _log(string $level, string $message, ?array $data = null): void
    {
        $level = mb_str_pad($level, 5, ' ', STR_PAD_RIGHT);
        $context = mb_substr(mb_str_pad($this->context, 16, ' ', STR_PAD_RIGHT), 0, 16);

        $fullMessage = "[{$level}] [{$context}] {$message}";

        if ($data !== null) {
            $allData = array_merge($this->_data, $data);
            $fullMessage .= ' ' . json_encode($allData, JSON_UNESCAPED_UNICODE);
        }

        error_log($fullMessage);
    }

    public function error(string $message, ?array $data = null): void
    {
        $this->_log('ERROR', $message, $data);
    }

    public function warning(string $message, ?array $data = null): void
    {
        $this->_log('WARN', $message, $data);
    }

    public function info(string $message, ?array $data = null): void
    {
        $this->_log('INFO', $message, $data);
    }

    public function debug(string $message, ?array $data = null): void
    {
        $this->_log('DEBUG', $message, $data);
    }

    public function addData(array $data): void
    {
        $this->setData(array_merge($this->_data, $data));
    }

    public function setData(array $data): void
    {
        $this->_data = $data;
    }
}
