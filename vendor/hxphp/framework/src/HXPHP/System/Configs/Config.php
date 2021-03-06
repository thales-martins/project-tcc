<?php

namespace HXPHP\System\Configs;

class Config extends Bootstrap
{
    public $global;

    public $env;

    public $define;

    public function __construct()
    {
        parent::__construct();

        $this->global = new GlobalConfig();
        $this->env = new Environment();
        $this->define = new DefineEnvironment();
        $this->env->add();
    }

    public function __get(string $param)
    {
        $current = $this->define->getDefault();

        if (isset($this->env->$current->$param)) {
            return $this->env->$current->$param;
        } elseif (isset($this->global->$param)) {
            return $this->global->$param;
        }

        throw new \Exception("Parametro/Modulo '$param' nao encontrado. Verifique se o ambiente definido esta configurado e os modulo utilizados registrados.", 1);
    }
}
