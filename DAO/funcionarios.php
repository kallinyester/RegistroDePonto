<?php

class funcionarios {
    public $id;
    public $nome;
    public $situacao;

    public function set($prop, $value) {
        $this->$prop = $value;
    }
    
    public function busca_por_situacao() {
        $objeto = new funcionariosDAO();
        $objeto->set("situacao", $this->situacao);
        return $objeto->busca_por_situacao();
    }

    public function cadastrar() {
        $objeto = new funcionariosDAO();
        $objeto->set("nome", $this->nome);
        $objeto->set("situacao", $this->situacao);
        $objeto->cadastrar();
    }

    public function alterar() {
        $objeto = new funcionariosDAO();
        $objeto->set("id", $this->id);
        $objeto->set("nome", $this->nome);
        $objeto->set("situacao", $this->situacao);
        return $objeto->alterar();
    }

    public function deletar() {
        $objeto = new funcionariosDAO();
        $objeto->set("id", $this->id);
        return $objeto->deletar();
    }
    
}

?>