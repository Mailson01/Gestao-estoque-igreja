<?php
// Inclui o arquivo de conexão com o banco de dados
include_once "../usuarios/conexao.php";

// Inicializa a mensagem de resposta
$msg = '';
$msg_type = '';

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados enviados pelo formulário
    $produto = $_POST['produto'] ?? '';
    $quantidade = $_POST['quantidade'] ?? '';
    $foto = $_FILES['foto'] ?? null;

    // Verifica se todos os campos foram preenchidos
    if (empty($produto) || empty($quantidade) || empty($foto['name'])) {
        $msg = "Por favor, preencha todos os campos e envie uma foto.";
        $msg_type = 'error';
    } else {
        // Verifica se o produto já existe
        $sql_verifica = "SELECT * FROM produtos WHERE produto = '$produto'";
        $resultado = mysqli_query($conexao, $sql_verifica);

        if (mysqli_num_rows($resultado) > 0) {
            $msg = "Erro: O produto já está cadastrado.";
            $msg_type = 'error';
        } else {
            // Processa o upload da foto
            $foto_nome = $foto['name'];
            $foto_tmp = $foto['tmp_name'];
            $foto_destino = "uploads/" . $foto_nome;

            if (move_uploaded_file($foto_tmp, $foto_destino)) {
                // Insere o produto na tabela 'produtos'
                $sql_produto = "INSERT INTO produtos (produto, quantidade, foto) VALUES ('$produto', '$quantidade', '$foto_destino')";
                if (mysqli_query($conexao, $sql_produto)) {
                    // Obtém o ID do produto inserido
                    $produto_id = mysqli_insert_id($conexao);

                    // Insere a quantidade inicial na tabela 'estoque'
                    $sql_estoque = "INSERT INTO estoque (id_produtos, quantidade_total) VALUES ('$produto_id', '$quantidade')";
                    if (mysqli_query($conexao, $sql_estoque)) {
                        $msg = "Produto e estoque cadastrados com sucesso!";
                        $msg_type = 'success';
                    } else {
                        $msg = "Erro ao cadastrar o estoque: " . mysqli_error($conexao);
                        $msg_type = 'error';
                    }
                } else {
                    $msg = "Erro ao cadastrar o produto: " . mysqli_error($conexao);
                    $msg_type = 'error';
                }
            } else {
                $msg = "Erro ao fazer upload da foto.";
                $msg_type = 'error';
            }
        }
    }
}

// Fecha a conexão com o banco de dados
mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f9f9f9;
}

.container {
    max-width: 500px;
    margin: 20px auto;
    background: #fff;
    padding: 20px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
}

.logo {
    text-align: center;
    margin-bottom: 20px;
}

.logo img {
    max-width: 40%;
    height: auto;
    max-height: 150px;
}

h1 {
    text-align: center;
    color: #333;
}

.form-content {
    display: flex;
    gap: 20px;
    align-items: flex-start;
    text-align: center;
}

.form-area {
    flex: 1;
}

label {
    font-weight: bold;
    color: #555;
}

input[type="text"], input[type="number"], input[type="file"] {
    padding: 10px;
    font-size: 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    width: 90%;
    margin-bottom: 15px;
}

button {
    padding: 10px 15px;
    font-size: 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-bottom: 10px;
}

.btn-cadastrar {
    background-color: #4CAF50;
    color: white;
}

.btn-voltar {
    background-color: #f44336;
    color: white;
}

.btn-cadastrar:hover {
    background-color: #45a049;
}

.btn-voltar:hover {
    background-color: #e53935;
}

.msg {
    padding: 10px;
    border-radius: 4px;
    margin-bottom: 15px;
}

.msg.success {
    background-color: #dff0d8;
    color: #3c763d;
}

.msg.error {
    background-color: #f2dede;
    color: #a94442;
}

/* Responsividade para tablets */
@media (max-width: 768px) {
    .container {
        max-width: 90%;
        padding: 15px;
    }

    h1 {
        font-size: 20px;
    }

    input[type="text"], input[type="number"], input[type="file"] {
        width: 100%;
    }

    .logo img {
        max-width: 60%; /* Ajusta o tamanho da logo */
    }

    button {
        width: 100%;
    }
}

/* Responsividade para celulares */
@media (max-width: 480px) {
    .container {
        max-width: 95%;
        padding: 10px;
    }

    h1 {
        font-size: 18px;
    }

    .logo img {
        max-width: 80%; /* Ajusta o tamanho da logo */
    }

    input[type="text"], input[type="number"], input[type="file"] {
        width: 100%;
        font-size: 14px;
    }

    button {
        font-size: 14px;
        padding: 8px;
    }
}

    </style>
</head>
<body>
    <div class="container">
        <!-- Logo da página -->
        <div class="logo">
            <img src="../assets/imgs/logo-horizontal.png" alt="Logo da Empresa">
        </div>
        
        <h1>Cadastrar Produto</h1>
        
        <?php if (!empty($msg)): ?>
            <div class="msg <?= $msg_type; ?>">
                <?= $msg; ?>
            </div>
        <?php endif; ?>
        
        <div class="form-content">
            <!-- Área do formulário -->
            <div class="form-area">
                <form method="POST" enctype="multipart/form-data">
                    <label for="produto">Nome do Produto:</label>
                    <input type="text" id="produto" name="produto" required>

                    <label for="quantidade">Quantidade Inicial:</label>
                    <input type="number" id="quantidade" name="quantidade" min="1" required>

                    <label for="foto">Foto do Produto:</label>
                    <input type="file" id="foto" name="foto" accept="image/*" required>

                    <button type="submit" class="btn-cadastrar">Cadastrar</button>
                    <a href="../telainicial.php"><button type="button" class="btn-voltar">Voltar para Tela Inicial</button></a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>

