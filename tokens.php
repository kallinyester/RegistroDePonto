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

    public function verifica_token($token){
        $con = new Conexao();

        // Verifica se o token existe e ainda não foi usado
        $sql = "SELECT * FROM tokens WHERE token = '$token';";
        $con->set("sql", $sql);
        $resultado = $con->query();

        return $resultado;
    }

    public function deleta_token($token){
        $con = new Conexao();

        $deleteToken = "DELETE FROM tokens WHERE token = '$token'";
        $con->set("sql", $deleteToken);
        $resultado = $con->query();

        return $resultado;
    }

    public function sede_gerar_token() {
        $objeto = new Conexao();
        
        // Verifica se tem tokens no banco de dados
        $verificaSQL = "SELECT * FROM tokens;";
        $objeto->set("sql", $verificaSQL);
        $verifica_resultado = $objeto->query($verificaSQL);

        // se tiver tokens
        if($verifica_resultado->num_rows != 0){
            
            $row = $verifica_resultado->fetch_assoc();
            $token = $row["token"];

            // se existir: cria o qrcode
            $url = "http://localhost/CH2/registro/sede.php?token=$token";
            QRcode::png($url, __DIR__ . '/sede_qrcode.png');

            return $token;
          
        } else{
            // Gera um número aleatório = token
            $token = random_int(1, 10000000);
            // Insere o token no banco de dados
            $SQL = "INSERT INTO tokens (token, usado) VALUES ('$token', 0)";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);

            // Gera a URL e o QR Code
            $url = "http://localhost/CH2/registro/sede.php?token=$token";
            QRcode::png($url, __DIR__ . '/sede_qrcode.png');

            return $token;
        }
    }

    public function rg_gerar_token() {
        $objeto = new Conexao();
        
        // Verifica se tem tokens no banco de dados
        $verificaSQL = "SELECT * FROM tokens;";
        $objeto->set("sql", $verificaSQL);
        $verifica_resultado = $objeto->query($verificaSQL);

        // se tiver tokens
        if($verifica_resultado->num_rows != 0){
            
            $row = $verifica_resultado->fetch_assoc();
            $token = $row["token"];

            // se existir: cria o qrcode
            $url = "http://localhost/CH2/registro/rg.php?token=$token";
            QRcode::png($url, __DIR__ . '/rg_qrcode.png');
          
        } else{
            // Gera um número aleatório = token
            $token = random_int(1, 10000000);
            // Insere o token no banco de dados
            $SQL = "INSERT INTO tokens (token, usado) VALUES ('$token', 0)";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);

            // Gera a URL e o QR Code
            $url = "http://localhost/CH2/registro/rg.php?token=$token";
            QRcode::png($url, __DIR__ . '/rg_qrcode.png');
        }
        
    }

    public function evento_gerar_token() {
        $objeto = new Conexao();
        
        // Verifica se tem tokens no banco de dados
        $verificaSQL = "SELECT * FROM tokens;";
        $objeto->set("sql", $verificaSQL);
        $verifica_resultado = $objeto->query($verificaSQL);

        // se tiver tokens
        if($verifica_resultado->num_rows != 0){
            
            $row = $verifica_resultado->fetch_assoc();
            $token = $row["token"];

            // se existir: cria o qrcode
            $url = "http://localhost/CH2/registro/evento.php?token=$token";
            QRcode::png($url, __DIR__ . '/evento_qrcode.png');
          
        } else{
            // Gera um número aleatório = token
            $token = random_int(1, 10000000);
            // Insere o token no banco de dados
            $SQL = "INSERT INTO tokens (token, usado) VALUES ('$token', 0)";
            $objeto->set("sql", $SQL);
            $objeto->query($SQL);

            // Gera a URL e o QR Code
            $url = "http://localhost/CH2/registro/evento.php?token=$token";
            QRcode::png($url, __DIR__ . '/evento_qrcode.png');
        }

    }
}
?>