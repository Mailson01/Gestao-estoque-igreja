<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <style>
         /* Reset básico */
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

        /* Contêiner do formulário */
        .form-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            max-width: 400px;
            width: 90%;
            text-align: center;
        }

        /* Título */
        h2 {
            color: #0984e3;
            margin-bottom: 15px;
            font-size: 1.8em;
        }

        /* Estilo da mensagem */
        .message {
            display: none; /* Oculta a mensagem por padrão */
            color: #d63031;
            margin-bottom: 15px;
            font-size: 0.9em;
        }

        /* Estilo das labels */
        label {
            display: block;
            color: black;
            font-size: 1em;
            margin-bottom: 5px;
            text-align: left;
        }

        /* Campos de entrada */
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #b2bec3;
            border-radius: 5px;
            outline: none;
            font-size: 1em;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #0984e3;
        }

        /* Botão de envio */
        button {
            width: 100%;
            padding: 10px;
            font-size: 1em;
            font-weight: bold;
            color: #ffffff;
            background-color: #0984e3;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-bottom: 10px;
        }

        button:hover {
            background-color: #74b9ff;
        }

        /* Botão de voltar */
        .back-button {
            display: inline-block;
            padding: 10px;
            font-size: 1em;
            font-weight: bold;
            color: #ffffff;
            background-color: #0984e3;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s;
            width: 100%;
        }

        .back-button:hover {
            background-color: #c0392b;
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

            input[type="text"], input[type="password"], button {
                font-size: 0.9em;
                padding: 8px;
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

            input[type="text"], input[type="password"], button {
                font-size: 1em;
                padding: 9px;
            }
        }

        /* Para telas grandes (desktop) */
        @media (min-width: 1025px) {
            .form-container {
                padding: 20px;
                max-width: 400px;
            }

            h2 {
                font-size: 1.8em;
            }

            input[type="text"], input[type="password"], button {
                font-size: 1em;
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <form action="verifca-cad-user.php" method="post">
            <h2>Cadastro de Usuário</h2>

            <!-- Exibe mensagem de erro ou sucesso -->
            <div id="message" class="message">Mensagem de erro ou sucesso aqui</div>

            <label for="loginn">Novo Login</label>
            <input type="text" name="loginn" id="loginn" required>
            
            <label for="password">Senha</label>
            <input type="password" name="senha" id="password" required>
            
            <button type="submit">Cadastrar</button>
        </form>

        <!-- Botão de voltar -->
        <a href="../telainicial.php" class="back-button">Voltar</a>
    </div>
</body>
</html>
