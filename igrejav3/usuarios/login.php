<?php
session_start();
if (isset($_SESSION['id'])) {
    header('Location: /telainicial.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paróquia São Francisco de Assis</title>
    <style>
        /* Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }

        /* Estilo do corpo */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #dfe6e9;
        }

        /* Estilo do contêiner do formulário */
        .form-container {
            background-color: #ffffff;
            padding: 20px 30px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 350px;
            width: 100%;
        }

        /* Estilo da imagem de logo */
        .login-logo {
            width: 80px;
            height: auto;
            margin-bottom: 20px;
        }

        /* Título */
        h2 {
            color: #2d3436;
            margin-bottom: 20px;
            font-size: 1.8em;
        }

        /* Estilo das labels e dos campos de entrada */
        label {
            display: block;
            color: #636e72;
            font-size: 0.9em;
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #b2bec3;
            border-radius: 5px;
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #0984e3;
        }

        /* Estilo dos botões */
        .button {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            font-weight: bold;
            color: #ffffff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        /* Estilo do botão de "Entrar" */
        .button {
            background-color: #7C5E3F;
        }

        .button:hover {
            background-color: #74b9ff;
        }

        /* Estilo do botão de "Cadastrar Usuário" */
        .register-button {
            background-color: #6c5ce7;
        }

        .register-button:hover {
            background-color: #a29bfe;
        }

        /* Media Queries para Responsividade */

        /* Para telas pequenas (celulares) */
        @media (max-width: 600px) {
            .form-container {
                padding: 15px;
                max-width: 100%;
            }

            h2 {
                font-size: 1.5em;
            }

            input[type="text"], input[type="password"], .button {
                font-size: 0.9em;
                padding: 8px;
            }

            .login-logo {
                width: 60px;
            }
        }

        /* Para tablets (largura entre 601px e 1024px) */
        @media (min-width: 601px) and (max-width: 1024px) {
            .form-container {
                padding: 18px;
                max-width: 80%;
            }

            h2 {
                font-size: 1.6em;
            }

            input[type="text"], input[type="password"], .button {
                font-size: 1em;
                padding: 9px;
            }

            .login-logo {
                width: 70px;
            }
        }

        /* Para telas grandes (desktop) */
        @media (min-width: 1025px) {
            .form-container {
                padding: 20px;
                max-width: 350px;
            }

            h2 {
                font-size: 1.8em;
            }

            input[type="text"], input[type="password"], .button {
                font-size: 1em;
                padding: 10px;
            }

            .login-logo {
                width: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="verifica-login.php" method="post">
            <img src="../assets/imgs/logo.jpg" alt="Logo" class="login-logo">
            <h2>Login</h2>
            <label for="loginn">Login</label>
            <input type="text" name="loginn" id="loginn" required>
            
            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required>
            
            <button class="button" type="submit">Entrar</button>
       
    </div>
</body>
</html>
