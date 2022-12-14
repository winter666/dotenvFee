<?php


namespace Serge\Dotenv;

use Serge\Config\Config;

class Env
{
    protected string $name;

    public function __construct(string $name = '.env')
    {
        $this->name = $name;
    }

    public static function getInstance(): static {
        return new static;
    }

    public function getAll(): array {
        $config = Config::getInstance()->get('server');
        $fileContent = file_get_contents(
            str_replace($config['public_path'],
                '',
                $_SERVER['DOCUMENT_ROOT']). '/' . $this->name
        );

        $rows = explode("\n", $fileContent);
        $data = [];
        foreach ($rows as $row) {
            $item = explode('=', $row);
            $name = $item[0];
            $value = $item[1] ?? null;
            if (!empty($name) && empty($data[$name])) {
                $data[trim($name)] = trim($value);
            }
        }

        return $data;
    }

    public function get(string $name): string|null {
        $all = $this->getAll();
        return $all[$name] ?? null;
    }
}
