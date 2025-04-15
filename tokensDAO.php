<?php

class tokensDAO {
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
        $objeto = new Conexao();

        do { // Gera um novo token aleatório
            $token = bin2hex(random_bytes(16));
            // Verifica se esse token já existe
            $verificaSQL = "SELECT * FROM tokens WHERE token = '$token'";
            $objeto->set("sql", $verificaSQL);
            $resultado = $objeto->query();
        } 
        while ($resultado && $resultado->num_rows > 0);

        // Insere o token no banco de dados
        $SQL = "INSERT INTO tokens (token, usado) VALUES ('$token', 0)";
        $objeto->set("sql", $SQL);
        $objeto->query($SQL);

        // Gera a URL e o QR Code
        $url = "http://localhost/CH/acaoE.php?token=$token";
        QRcode::png($url, __DIR__ . '/qrcode.png');

    }

    public function T_gerar_token() {
        $objeto = new Conexao();

        do { // Gera um novo token aleatório
            $token = bin2hex(random_bytes(16));
            // Verifica se esse token já existe
            $verificaSQL = "SELECT * FROM tokens WHERE token = '$token'";
            $objeto->set("sql", $verificaSQL);
            $resultado = $objeto->query();
        } 
        while ($resultado && $resultado->num_rows > 0);

        // Insere o token no banco de dados
        $SQL = "INSERT INTO tokens (token, usado) VALUES ('$token', 0)";
        $objeto->set("sql", $SQL);
        $objeto->query($SQL);

        // Gera a URL e o QR Code
        $url = "http://localhost/CH/acaoT.php?token=$token";
        QRcode::png($url, __DIR__ . '/qrcode.png');

    }
}
?>
