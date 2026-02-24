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

    public function sede_gerar_qrcode() {
        // cria o qrcode
        $url = "http://localhost/CH2/registro/sede.php";
        QRcode::png($url, __DIR__ . '/sede_qrcode.png');
    }

    public function rg_gerar_qrcode() {
        // cria o qrcode
        $url = "http://localhost/CH2/registro/rg.php";
        QRcode::png($url, __DIR__ . '/rg_qrcode.png');
    }

    public function evento_gerar_qrcode() {
        // se existir: cria o qrcode
        $url = "http://localhost/CH2/registro/evento.php";
        QRcode::png($url, __DIR__ . '/evento_qrcode.png');
    }
}
?>