<?php

class funcionarios {
    public $id;
    public $nome;

    public function efetivos_buscar() {
        $objeto = new funcionariosDAO();
        return $objeto->efetivos_buscar();
    }

    public function trainees_buscar() {
        $objeto = new funcionariosDAO();
        return $objeto->trainees_buscar();
    }

    public function set($prop, $value) {
        $this->$prop = $value;
    }
}

?>