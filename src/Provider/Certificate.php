<?php
/**
 * Created by PhpStorm.
 * User: sasa.blagojevic@mail.com
 * Date: 27/08/2020
 * Time: 14:39
 */

namespace SasaB\Apns\Provider;


final class Certificate implements Trust
{
    private $file;

    private $content;

    public function __construct(string $file)
    {
        $this->file = $file;
        $this->content = file_get_contents($file);
    }

    public static function fromFile(string $path): Certificate
    {
        if (!file_exists($path)) throw new \InvalidArgumentException("File [$path] not found");

        return new self($path);
    }

    public function getAuthOptions(): array
    {
        return [
            'cert' => ''
        ];
    }
}