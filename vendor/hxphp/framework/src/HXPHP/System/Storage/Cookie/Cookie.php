<?php

namespace HXPHP\System\Storage\Cookie;

class Cookie
{
    /**
     * Cria um cookie.
     *
     * @param string    $name  Nome do cookie
     * @param string    $value Conteúdo do cookie
     * @param timestamp $time  Tempo de duração do cookie
     */
    public function set(string $name, string $value, int $time = 31556926): self
    {
        $cookieParams = session_get_cookie_params();

        setcookie($name, $value, time() + $time, $cookieParams['path'], $cookieParams['domain'], false, true);

        return $this;
    }

    /**
     * Seleciona um cookie.
     *
     * @param string $name Nome do cookie
     *
     * @return string Conteúdo do cookie
     */
    public function get(string $name)
    {
        if ($this->exists($name)) {
            return $_COOKIE[$name];
        }
    }

    /**
     * Verifica a existência do cookie.
     *
     * @param string $name Nome do cookie
     *
     * @return bool Status do processo
     */
    public function exists(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Exclui um cookie.
     *
     * @param string $name Nome do cookie
     */
    public function clear(string $name)
    {
        if ($this->exists($name)) {
            return $this->set($name, '', -1);
        }
    }
}
