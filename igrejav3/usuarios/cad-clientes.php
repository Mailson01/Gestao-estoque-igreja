

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Cliente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Estilos para o formulário */
        form {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

        label {
            color: #333;
            font-size: 14px;
            margin-bottom: 5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        button {
            padding: 10px;
            background-color: #5c3d15;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin: 5px 0;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Estilos para os botões de navegação */
        .back-btn {
            background-color: #45a049;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        /* Caixa de resultados */
        .resultado {
            background-color: #fff;
            padding: 10px;
            margin-top: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
        }

        .erro {
            color: red;
            font-weight: bold;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            h1 {
                font-size: 20px;
            }

            form {
                padding: 20px;
                max-width: 100%;
            }

            input[type="text"], button {
                font-size: 12px;
            }

            .back-btn {
                font-size: 12px;
                padding: 8px 16px;
            }

            .resultado {
                width: 100%;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 18px;
            }

            .back-btn {
                font-size: 10px;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>

    <h1>Cadastro de Cliente</h1>
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" required>

        <button type="submit">Cadastrar</button>

        <!-- Botões dentro do formulário -->
        <a href="../telainicial.php" class="back-btn">Voltar</a>
        <a href="consulta-clientes.php" class="back-btn">Consultar Clientes Cadastrados</a>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Conectar ao banco de dados
        include_once "./conexao.php";

        // Captura os dados do formulário
        $nome = $_POST['nome'];
        $telefone = $_POST['telefone'];

        // Verifica se os campos não estão vazios
        if (empty($nome) || empty($telefone)) {
            echo "<div class='resultado erro'>Por favor, preencha todos os campos.</div>";
        } else {
            // Verifica se já existe um cliente com o mesmo nome
            $sql_check = "SELECT * FROM clientes WHERE nome = '$nome'";
            $result = mysqli_query($conexao, $sql_check);

            if (mysqli_num_rows($result) > 0) {
                echo "<div class='resultado erro'>Já existe um cliente com o nome '$nome'.</div>";
            } else {
                // Insere os dados na tabela 'clientes'
                $sql = "INSERT INTO clientes (nome, telefone) VALUES ('$nome', '$telefone')";

                if (mysqli_query($conexao, $sql)) {
                    echo "<div class='resultado'>Cliente cadastrado com sucesso!</div>";
                } else {
                    echo "<div class='resultado erro'>Erro ao cadastrar cliente: " . mysqli_error($conexao) . "</div>";
                }
            }
        }

        // Fechar a conexão
        mysqli_close($conexao);
    }
    ?>

</body>
</html>
