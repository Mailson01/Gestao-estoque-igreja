<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Produto no Estoque</title>
    <style>
        /* Estilo existente */
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
            margin-bottom: 10px;
        }
        .search-form input[type="text"] {
            padding: 4px;
            font-size: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 340px;
            margin-right: 5px;
            text-align: center;
        }
        .search-form button {
            padding: 4px;
            font-size: 10px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 20px;
            height: 20px;
        }
        .search-form button:hover {
            background: #2980b9;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
        }
        .product-table th, .product-table td {
            padding: 6px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .product-table th {
            background: #5c3d15;
            color: white;
        }
        .product-table tr:hover {
            background: #f4f4f4;
        }
        .product-table td img {
            max-width: 50px;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .product-table td img:hover {
            transform: scale(2);
        }
        .btn {
            padding: 4px 20px;
            font-size: 14px;
            background-color: #5c3d15;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            margin: 10px 5px;
            margin-left: 50px;
           
        }
        .btn:hover {
            background-color: green;
        }
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
        include_once "conexao.php";

        $filtro = "";
        if (isset($_POST['produto']) && !empty($_POST['produto'])) {
            $produto = $_POST['produto'];
            $filtro = "WHERE p.produto LIKE '%$produto%'";
        }

        // Consulta ajustada para somar as quantidades no estoque
        $consulta = "
        SELECT p.produto, p.foto, IFNULL(SUM(e.quantidade_total), 0) AS quantidade_total
        FROM produtos p
        LEFT JOIN estoque e ON p.id_produtos = e.id_produtos
        $filtro
        GROUP BY p.produto, p.foto, p.id_produtos
    ";

        $result = mysqli_query($conexao, $consulta);

        if (mysqli_num_rows($result) > 0) {
            echo "<table class='product-table'>";
            echo "<thead><tr><th>Foto</th><th>Produto</th><th>Quantidade no Estoque</th></thead><tbody>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td><img src='" . htmlspecialchars($row['foto']) . "' alt='Foto do Produto'></td>";
                echo "<td>" . htmlspecialchars($row['produto']) . "</td>";
                echo "<td>" . htmlspecialchars($row['quantidade_total']) . "</td>";
                echo "</tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p class='erro'>Nenhum produto encontrado no estoque.</p>";
        }

        mysqli_close($conexao);
        ?>

        <!-- Botões de navegação -->
        <a href="javascript:history.back()" class="btn">Voltar</a>
        <a href="telainicial.php" class="btn">Tela Inicial</a>
    </div>
</body>
</html>
