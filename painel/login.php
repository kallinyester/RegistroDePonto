<?php
session_start();

require('C:\xampp\htdocs\RegistroPonto\DAO\Conexao.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\registrosDAO.php');
require('C:\xampp\htdocs\RegistroPonto\DAO\registros.php');

if (!empty($_POST)){
    $objeto = new registrosDAO();
    $objeto->set("usuario", $_POST["usuario"]);
    $objeto->set("senha", $_POST["senha"]);
    $objeto->validarLogin();    
}
?>


<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <link rel="icon" type="image/png" href="https://i.postimg.cc/NMjbycNV/AdeApoio.png">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
      * {
          box-sizing: border-box;
          margin: 0;
          padding: 0;
          font-family: 'Poppins', sans-serif;
      }

      body {
          color: white;
          background-color:rgb(40, 40, 88);
          height: 100%;
          display: flex;
          justify-content: center;
          align-items: center;
          min-height: 100vh;
      }

      .login-wrapper {
          background-color: #f0f4f8;
          display: flex;
          border-radius: 12px;
          box-shadow: 0 8px 24px rgba(0,0,0,0.1);
          overflow: hidden;
          max-width: 900px;
          width: 100%;
          height: 500px;
      }

      .login-image {
          flex: 1;
          display: flex;
          justify-content: center;
          align-items: center;
          padding: 20px;
      }

      .login-image img {
          max-width: 100%;
          max-height: 100%;
          object-fit: contain;
      }

      .login-form {
          flex: 1;
          padding: 40px;
          display: flex;
          flex-direction: column;
          justify-content: center;
      }

      .login-form h2 {
          text-align: center;
          margin-bottom: 30px;
          color: #333;
      }

      .input-group {
          margin-bottom: 20px;
      }

      .input-group label {
          font-size: 14px;
          color: #333;
          margin-bottom: 6px;
          display: block;
      }

      .input-group input {
          width: 100%;
          padding: 12px;
          border: 1px solid #ccc;
          border-radius: 8px;
          font-size: 14px;
      }

      .login-btn {
          background-color:rgb(40, 40, 88);
          color: white;
          width: 100%;
          padding: 12px;
          font-size: 16px;
          border: none;
          border-radius: 8px;
          cursor: pointer;
          transition: background 0.3s ease;
      }

      .login-btn:hover {
          background:rgb(33, 54, 84);
      }

      @media (max-width: 768px) {
          .login-wrapper {
              flex-direction: column;
              height: auto;
          }

          .login-image {
              height: 200px;
          }

          .login-form {
              padding: 30px 20px;
          }
      }
    </style>
</head>
<body>
  <div class="login-wrapper">
    <div class="login-image">
      <img src="https://i.postimg.cc/13H5gqZf/Apoio.png" alt="Login Ilustração" style="width: 300px;" />
    </div>
    <div class="login-form">
      <h2><i class="fas fa-users"></i> Login</h2>

      <form action="" method="POST">

        <div class="input-group">
          <label for="username"><i class="fas fa-user"></i> Usuário</label>
          <input type="text" id="usuario" name="usuario" required />
        </div>
        <div class="input-group">
          <label for="password"><i class="fas fa-lock"></i> Senha</label>
          <input type="password" id="senha" name="senha" required />
        </div>
        <input class="login-btn" type="submit" value="Entrar" />
        
      </form>
    </div>
  </div>
</body>
</html>
