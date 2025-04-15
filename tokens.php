<?php

class tokens {
    public $id;
    public $usado;
    public $criado_em;

    public function set($prop, $value) {
        $this->$prop = $value;
    }
    public function get($prop) {
        return isset($this->$prop) ? $this->$prop : null;
    }

    public function E_gerar_token() {
        $objeto = new tokensDAO();
        return $objeto->E_gerar_token();
    }

    public function T_gerar_token() {
        $objeto = new tokensDAO();
        return $objeto->T_gerar_token();
    }
}

?>