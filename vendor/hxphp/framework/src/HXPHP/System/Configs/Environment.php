<?php

namespace HXPHP\System\Configs;

use HXPHP\System\Tools;

class Environment
{
    public $defaultEnvironment;

    public function __construct()
    {
        $define = new DefineEnvironment();
        $this->defaultEnvironment = $define->getDefault();

        $this->add();
    }

    public function add(string $environment = null)
    {
        if (!$environment) {
            $environment = $this->defaultEnvironment;
        }

        $name = strtolower(Tools::filteredName($environment));
        $object = 'HXPHP\System\Configs\Environments\\'.ucfirst(Tools::filteredName($environment));

        if (!class_exists($object)) {
            throw new \Exception('O ambiente informado nao esta definido nas configuracoes do sistema.');
        } else {
            $this->$name = new $object();

            return $this->$name;
        }
    }
}
