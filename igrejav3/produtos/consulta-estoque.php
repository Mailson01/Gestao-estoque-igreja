<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Produto no Estoque</title>
    <style>
    /* Estilo global */
    body {
        font-family: Arial, sans-serif;
        background: #f9f9f9;
        margin: 0;
        padding: 20px;
    }

    h1 {
        color: #5c3d15;
        text-align: center;
        margin-bottom: 20px;
    }

    .container {
        max-width: 1000px;
        margin: auto;
        padding: 10px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .search-form {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }

    .search-form input[type="text"] {
        padding: 6px;
        font-size: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        width: 100%;
        max-width: 300px;
        margin-bottom: 10px;
        text-align: center;
    }

    .search-form button {
        padding: 6px;
        font-size: 12px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        width: 30px;
        height: 25px;
        margin-top: -12px;
    }

    .search-form button:hover {
        background: #2980b9;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
    }

    .product-table th,
    .product-table td {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid #ddd;
        font-size: 12px;
    }

    .product-table th {
        background: #5c3d15;
        color: white;
    }

    .product-table tr:hover {
        background: #f4f4f4;
    }

    .product-table td img {
        max-width: 40px;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.3s;
    }

    .product-table td img:hover {
        transform: scale(2);
    }
/* Centralizar os botões */
.button-container {
    display: flex; /* Define um layout flexível */
    justify-content: center; /* Centraliza os botões horizontalmente */
    gap: 20px; /* Espaço entre os botões */
    margin-top: 20px; /* Espaço acima dos botões */
}

/* Estilo individual dos botões */
.btn {
    padding: 10px 20px; /* Ajusta o tamanho dos botões */
    font-size: 14px; /* Aumenta o tamanho da fonte */
    background-color: #5c3d15;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: green; /* Cor do botão ao passar o mouse */
}


    /* Botões na tabela para Editar e Excluir */
    .btn-action {
        padding: 4px 8px;
        font-size: 10px;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-action.edit {
        background-color: orange;
    }

    .btn-action.delete {
        background-color: red;
    }

    .btn-action:hover {
        opacity: 0.8;
    }

    /* Responsividade */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        h1 {
            font-size: 20px;
        }

        .search-form input[type="text"] {
            width: 100%;
            max-width: none;
        }

        .product-table th,
        .product-table td {
            font-size: 11px;
            padding: 6px;
        }

        .btn {
            font-size: 10px;
            padding: 4px 8px;
            
        }

        .btn-action {
            font-size: 8px;
            padding: 3px 6px;
            
        }
    }

    @media (max-width: 480px) {
        .product-table td img {
            max-width: 30px;
        }

        .search-form {
            flex-direction: column;
        }

        .search-form button {
            width: 100%;
            max-width: 100px;
        }
    }


</style>

    </style>
</head>
<body>
    <div class="container">
        <h1>Consulta de Produtos no Estoque</h1>

        <form method="POST" class="search-form">
            <input type="text" name="produto" id="produto" placeholder="Consultar Produto" value="">
            <button type="submit">🔍</button>
        </form>

        <?php
        include_once "../usuarios/conexao.php";

        $filtro = "";
        if (isset($_POST['produto']) && !empty($_POST['produto'])) {
            $produto = $_POST['produto'];
            $filtro = "WHERE p.produto LIKE '%$produto%'";
        }

        // Consulta ajustada para somar as quantidades no estoque
        $consulta = "
        SELECT p.produto, p.foto, IFNULL(SUM(e.quantidade_total), 0) AS quantidade_total, p.id_produtos
        FROM produtos p
        LEFT JOIN estoque e ON p.id_produtos = e.id_produtos
        $filtro
        GROUP BY p.produto, p.foto, p.id_produtos
    ";

        $result = mysqli_query($conexao, $consulta);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='product-table'>";
            echo "<thead><tr><th>Foto</th><th>Produto</th><th>Quantidade no Estoque</th><th>Ações</th></tr></thead><tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><img src='" . htmlspecialchars($row['foto']) . "' alt='Foto do Produto'></td>";
                echo "<td>" . htmlspecialchars($row['produto']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantidade_total']) . "</td>";
                echo "<td>";
                echo "<a href='editar-estoque.php?id=" . $row['id_produtos'] . "' class='btn-action edit'>Editar</a>";
                echo "<a href='excluir-produto.php?id=" . $row['id_produtos'] . "' class='btn-action delete'>Excluir</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='erro'>Nenhum produto encontrado no estoque.</p>";
        }

        mysqli_close($conexao);
        ?>

        <!-- Botões de navegação -->
        <div class="container">
    <!-- Conteúdo principal do container -->

    <!-- Botões -->
    <div class="button-container">
        <a href="javascript:history.back()" class="btn">Voltar</a>
        <a href="../telainicial.php" class="btn">Tela Inicial</a>
        </div>
        </div>
    </div>
</body>
</html>
