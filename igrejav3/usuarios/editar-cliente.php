<?php
// Conexão com o banco de dados
include_once "conexao.php";


// Verificar se o id_cliente foi passado via URL
if (isset($_GET['id_cliente'])) {
    $id_cliente = $_GET['id_cliente'];

    // Consultar o cliente pelo ID
    $sql = "SELECT * FROM clientes WHERE id_cliente = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verifica se o cliente existe
    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
    } else {
        die("Cliente não encontrado.");
    }
} else {
    die("ID do cliente não informado.");
}

// Verificar se o formulário foi submetido para atualizar os dados
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
   
    // Atualizar o cliente no banco de dados
    $update_sql = "UPDATE clientes SET nome = ?, telefone = ?, endereco = ? WHERE id_cliente = ?";
    $update_stmt = $conexao->prepare($update_sql);
    $update_stmt->bind_param("sssi", $nome, $telefone, $endereco, $id_cliente);

    if ($update_stmt->execute()) {
        echo "<div class='resultado'>Dados do cliente atualizados com sucesso!</div>";
    } else {
        echo "<div class='resultado erro'>Erro ao atualizar os dados: " . $conexao->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cliente</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
        }

        /* Formulário */
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: 0 auto;
            display: flex;
            flex-direction: column;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 14px;
            color: #333;
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
            width: 100%;
            padding: 10px 0;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Botões de navegação */
        .back-btn {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #45a049;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            text-align: center;
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
            form {
                max-width: 100%;
            }

            input[type="text"], button {
                font-size: 12px;
            }

            .back-btn {
                font-size: 12px;
                padding: 8px 16px;
            }
        }
    </style>
</head>
<body>

    <h1>Editar Cliente</h1>

    <!-- Formulário de Edição -->
    <form method="POST" action="">
        <label for="nome">Nome:</label>
        <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>

        <label for="telefone">Telefone:</label>
        <input type="text" name="telefone" id="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>

        <button type="submit">Atualizar</button>
    </form>

    <!-- Botão de Voltar -->
    <a href="consulta-clientes.php" class="back-btn">Voltar para Consultar Clientes</a>

</body>
</html>
