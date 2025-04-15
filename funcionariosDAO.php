<?php

class funcionariosDAO {
    public $id;
    public $nome;

    public function efetivos_buscar() {
        $objeto = new Conexao();
        $SQL = "SELECT id, nome FROM efetivos;";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function trainees_buscar() {
        $objeto = new Conexao();
        $SQL = "SELECT id, nome FROM trainees;";
        $objeto->set("sql", $SQL);
        $resultados = $objeto->query()->fetch_all(MYSQLI_ASSOC);
        return $resultados;
    }

    public function set($prop, $value) {
        $this->$prop = $value;
    }
}

?>