<?php
include_once "../usuarios/conexao.php"; // Conexão com o banco de dados

// Verificar se o ID do produto foi passado corretamente pela URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_produto = (int)$_GET['id']; // Recupera o ID do produto
} else {
    die("ID de produto inválido ou não especificado!");
}

// Buscar o produto atual para mostrar na tela
$query = "
    SELECT p.produto, e.quantidade_total, p.foto 
    FROM produtos p
    INNER JOIN estoque e ON p.id_produtos = e.id_produtos
    WHERE p.id_produtos = $id_produto
";

$result = mysqli_query($conexao, $query);
if (mysqli_num_rows($result) > 0) {
    $produto_data = mysqli_fetch_assoc($result);
} else {
    die("Produto não encontrado no banco de dados.");
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produto_nome = $_POST['produto_nome'];
    $quantidade_total = (int)$_POST['quantidade_total'];
    $foto = $produto_data['foto']; // Mantém a foto atual, caso não seja alterada

    // Verifica se foi enviada uma nova imagem
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_temp = $_FILES['foto']['tmp_name'];
        $foto_nome = $_FILES['foto']['name'];
        $foto_extensao = pathinfo($foto_nome, PATHINFO_EXTENSION);
        $foto_novo_nome = "uploads/" . uniqid() . "." . $foto_extensao;

        // Mover a nova imagem para a pasta 'uploads'
        if (move_uploaded_file($foto_temp, $foto_novo_nome)) {
            $foto = $foto_novo_nome; // Atualiza o caminho da foto no banco
        } else {
            echo "<p>Erro ao fazer upload da imagem.</p>";
        }
    }

    // Atualizar o nome do produto, quantidade e a imagem no banco de dados
    $update_query = "
        UPDATE produtos p
        JOIN estoque e ON p.id_produtos = e.id_produtos
        SET p.produto = '$produto_nome', e.quantidade_total = $quantidade_total, p.foto = '$foto'
        WHERE p.id_produtos = $id_produto
    ";

    if (mysqli_query($conexao, $update_query)) {
        echo "<p>Produto atualizado com sucesso!</p>";
    } else {
        echo "<p>Erro ao atualizar o produto.</p>";
    }
}

mysqli_close($conexao);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .back-btn {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-container input {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 100%;
        }

        .form-container button {
            padding: 12px;
            font-size: 14px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form-container button:hover {
            background-color: #2980b9;
        }

        .form-container img {
            max-width: 100px;
            height: auto;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .container {
                max-width: 100%;
                padding: 15px;
            }

            .back-btn {
                font-size: 12px;
                padding: 8px 5px;
            }

            .form-container button {
                font-size: 12px;
                padding: 10px;
            }

            .form-container input {
                font-size: 13px;
                padding: 8px;
            }
        }

        @media (max-width: 480px) {
            .back-btn {
                display: block;
                text-align: center;
                width: 100%;
                margin-bottom: 15px;
            }

            .form-container img {
                max-width: 70px;
            }

            h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <a href="consulta-estoque.php" class="back-btn">Voltar para Consulta de Estoque</a>
    <h1>Editar Produto</h1>

    <form method="POST" class="form-container" enctype="multipart/form-data">
        <label for="produto_nome">Nome do Produto</label>
        <input type="text" id="produto_nome" name="produto_nome" value="<?php echo $produto_data['produto']; ?>" required>

        <label for="quantidade_total">Quantidade no Estoque</label>
        <input type="number" id="quantidade_total" name="quantidade_total" value="<?php echo $produto_data['quantidade_total']; ?>" required>

        <label for="foto">Imagem do Produto</label>
        <input type="file" id="foto" name="foto">
        <img src="<?php echo $produto_data['foto']; ?>" alt="Imagem do Produto">

        <button type="submit">Salvar Alterações</button>
    </form>
</div>

</body>
</html>
