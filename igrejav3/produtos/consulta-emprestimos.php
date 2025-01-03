<?php
include_once "../usuarios/conexao.php"; // Conectar ao banco de dados

// Inicialização da variável para pesquisa, caso queira filtrar por nome
$nome_cliente = isset($_POST['nome_cliente']) ? $_POST['nome_cliente'] : '';

// Processar a devolução quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['quantidade_devolvida']) && isset($_POST['id_emprestimo']) && isset($_POST['id_produto'])) {
    $quantidade_devolvida = $_POST['quantidade_devolvida'];
    $id_emprestimo = $_POST['id_emprestimo'];
    $id_produto = $_POST['id_produto'];

    // Consultar a quantidade que o cliente pegou
    $sql_verificar_quantidade = "SELECT ep.quantidade FROM emprestimos_produtos ep WHERE ep.id_emprestimo = ? AND ep.id_produto = ?";
    $stmt_verificar_quantidade = mysqli_prepare($conexao, $sql_verificar_quantidade);
    mysqli_stmt_bind_param($stmt_verificar_quantidade, 'ii', $id_emprestimo, $id_produto);
    mysqli_stmt_execute($stmt_verificar_quantidade);
    $result_quantidade = mysqli_stmt_get_result($stmt_verificar_quantidade);
    
    if ($row = mysqli_fetch_assoc($result_quantidade)) {
        $quantidade_pegada = $row['quantidade'];
    }

    // Verificar se a quantidade devolvida é menor ou igual à quantidade que o cliente pegou
    if ($quantidade_devolvida > $quantidade_pegada) {
        die("A quantidade devolvida não pode ser maior que a quantidade emprestada.");
    }

    // Iniciar a transação para garantir a consistência dos dados
    mysqli_begin_transaction($conexao);

    try {
        // Atualizar o estoque - adicionar de volta a quantidade devolvida do produto
        $sql_estoque = "UPDATE estoque e
                        JOIN produtos p ON e.id_produtos = p.id_produtos
                        SET e.quantidade_total = e.quantidade_total + ?
                        WHERE p.id_produtos = ?";
        $stmt_estoque = mysqli_prepare($conexao, $sql_estoque);
        mysqli_stmt_bind_param($stmt_estoque, 'ii', $quantidade_devolvida, $id_produto);
        mysqli_stmt_execute($stmt_estoque);

        // Atualizar a quantidade do produto no empréstimo, se a devolução não for total
        if ($quantidade_pegada > $quantidade_devolvida) {
            // Atualizar a quantidade no empréstimo
            $sql_atualizar_emprestimo_produto = "UPDATE emprestimos_produtos 
                                                 SET quantidade = quantidade - ? 
                                                 WHERE id_emprestimo = ? AND id_produto = ?";
            $stmt_atualizar_emprestimo_produto = mysqli_prepare($conexao, $sql_atualizar_emprestimo_produto);
            mysqli_stmt_bind_param($stmt_atualizar_emprestimo_produto, 'iii', $quantidade_devolvida, $id_emprestimo, $id_produto);
            mysqli_stmt_execute($stmt_atualizar_emprestimo_produto);
        } else {
            // Se devolveu toda a quantidade, então pode excluir o produto do empréstimo
            $sql_excluir_emprestimo_produto = "DELETE FROM emprestimos_produtos WHERE id_emprestimo = ? AND id_produto = ?";
            $stmt_excluir_emprestimo_produto = mysqli_prepare($conexao, $sql_excluir_emprestimo_produto);
            mysqli_stmt_bind_param($stmt_excluir_emprestimo_produto, 'ii', $id_emprestimo, $id_produto);
            mysqli_stmt_execute($stmt_excluir_emprestimo_produto);
        }

        // Se todas as operações foram bem-sucedidas, confirmar a transação
        mysqli_commit($conexao);

        // Redirecionar para a tela de consulta
        header("Location: consulta-emprestimos.php");
        exit;

    } catch (Exception $e) {
        // Em caso de erro, desfazer a transação
        mysqli_rollback($conexao);
        die("Erro ao devolver o produto: " . $e->getMessage());
    }
}

// Consulta SQL para obter os empréstimos, agora agrupados por cliente
$sql = "SELECT c.id_cliente, c.nome AS nome_cliente, p.produto, ep.quantidade, e.data_emprestimo, e.id_emprestimo, p.id_produtos
        FROM emprestimos e
        JOIN clientes c ON e.id_cliente = c.id_cliente
        JOIN emprestimos_produtos ep ON e.id_emprestimo = ep.id_emprestimo
        JOIN produtos p ON ep.id_produto = p.id_produtos
        WHERE c.nome LIKE ? 
        ORDER BY e.data_emprestimo DESC";

// Preparar a consulta
$stmt = mysqli_prepare($conexao, $sql);

// Verificar se a consulta foi preparada corretamente
if ($stmt === false) {
    die("Erro na preparação da consulta: " . mysqli_error($conexao));
}

// Adicionar o filtro de pesquisa no nome do cliente
$like_nome = "%$nome_cliente%";
mysqli_stmt_bind_param($stmt, "s", $like_nome);

// Executar a consulta
mysqli_stmt_execute($stmt);

// Obter o resultado da consulta
$result = mysqli_stmt_get_result($stmt);

// Verificar se a consulta retornou algum resultado
if ($result === false) {
    die("Erro ao obter resultados da consulta: " . mysqli_error($conexao));
}

// Fechar a conexão após o uso
mysqli_close($conexao);
?>

<!-- HTML continua igual -->

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Empréstimos</title>
    <style>
         /* Estilos gerais */
         body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        .input-quantidade {
            width: 60px;
        }

        .btn {
            padding: 10px 15px;
            cursor: pointer;
            background-color: #8B6D3B;
            color: white;
            border: none;
            border-radius: 5px;
        }

        .btn:hover {
            background-color: #7C5E3F;
        }

        .cliente-info {
            font-weight: bold;
            font-size: 1.2em;
            color: #3E2A47;
            text-align: center;
            margin-top: 20px;
        }

        .imprimir-btn {
            background-color: #8B6D3B;
            color: white;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            margin-left: 10px;
        }

        .imprimir-btn:hover {
            background-color: #7C5E3F;
        }

        h1 {
            text-align: center;
            font-size: 2em;
            color: #8B6D3B;
            margin-top: 10px;
        }

        .search-form {
            text-align: center;
            margin-top: 20px;
        }

        .search-form input[type="text"] {
            padding: 10px;
            width: 80%;
            max-width: 400px;
            font-size: 1em;
        }

        .navigation-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .navigation-buttons .btn {
            margin: 0 10px;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            table th, table td {
                font-size: 0.9em;
                padding: 8px;
            }

            .btn, .imprimir-btn {
                padding: 8px 10px;
                font-size: 0.9em;
            }

            .input-quantidade {
                width: 50px;
                font-size: 0.9em;
            }

            h1 {
                font-size: 1.5em;
            }

            .cliente-info {
                font-size: 1em;
            }

            .search-form input[type="text"] {
                width: 90%;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            table {
                font-size: 0.8em;
            }

            .btn, .imprimir-btn {
                font-size: 0.8em;
                padding: 5px 8px;
            }

            .input-quantidade {
                font-size: 0.8em;
            }

            h1 {
                font-size: 1.2em;
            }

            .cliente-info {
                font-size: 0.9em;
            }
        }
    </style>
    <script>
     function imprimir(clienteId) {
    console.log("Cliente ID:", clienteId);

    // Criar uma nova janela para a impressão
    var novaJanela = window.open('', '', 'width=800,height=600');

    // Adicionar conteúdo para impressão
    novaJanela.document.write('<html><head><title>Impressão</title><style>');
    novaJanela.document.write('table {width: 100%; border-collapse: collapse; text-align: center;}');
    novaJanela.document.write('th, td {padding: 8px; border: 1px solid #ddd;}');
    novaJanela.document.write('h1 {text-align: center;}</style></head><body>');

    // Adicionar o título e o nome do cliente
    var nomeCliente = document.getElementById('cliente_nome_' + clienteId).innerText;
    novaJanela.document.write('<h1>Empréstimos<br>' + nomeCliente + '</h1>');

    // Adicionar a tabela com os dados (sem a coluna "Devolução")
    novaJanela.document.write('<table><thead><tr><th>Produto</th><th>Quantidade</th><th>Data de Empréstimo</th></tr></thead><tbody>');

    // Preencher a tabela com os dados
    var emprestimos = document.getElementsByClassName('emprestimo_' + clienteId);
    for (var i = 0; i < emprestimos.length; i++) {
        var produto = emprestimos[i].cells[0].innerText;
        var quantidade = emprestimos[i].cells[1].innerText;
        var dataEmprestimo = emprestimos[i].cells[2].innerText;

        // Adicionar cada linha à tabela sem a coluna de devolução
        novaJanela.document.write('<tr><td>' + produto + '</td><td>' + quantidade + '</td><td>' + dataEmprestimo + '</td></tr>');
    }

    novaJanela.document.write('</tbody></table>');
    novaJanela.document.write('</body></html>');

    // Fechar a janela e iniciar a impressão
    novaJanela.document.close();
    novaJanela.print();
}
    </script>
</head>
<body>
    <div class="container">
        <h1>Consulta de Empréstimos</h1>
        <form method="POST" class="search-form">
            <input type="text" name="nome_cliente" placeholder="Buscar Cliente" value="<?php echo htmlspecialchars($nome_cliente); ?>">
            <input type="submit" class="btn" value="Pesquisar">
        </form>

        <?php
        if (isset($result) && mysqli_num_rows($result) > 0) {
            // Iterar sobre os empréstimos
            $clientes = [];
            while ($produto = mysqli_fetch_assoc($result)) {
                $clientes[$produto['id_cliente']][] = $produto;
            }

            foreach ($clientes as $id_cliente => $produtos) {
                echo "<div class='cliente-info' id='cliente_nome_" . $id_cliente . "'>";
                echo "" . $produtos[0]['nome_cliente'] . " (" . count($produtos) . " produtos)";
                echo "<button class='imprimir-btn' onclick='imprimir(" . $id_cliente . ")'>IMPRIMIR</button>";
                echo "</div>";

                echo "<table>";
                echo "<thead><tr><th>Produto</th><th>Quantidade</th><th>Data de Empréstimo</th><th>Devolução</th></tr></thead><tbody>";

                foreach ($produtos as $produto) {
                    echo "<tr class='emprestimo_" . $id_cliente . "'>";
                    echo "<td>" . htmlspecialchars($produto['produto']) . "</td>";
                    echo "<td>" . htmlspecialchars($produto['quantidade']) . "</td>";
                    echo "<td>" . date("d/m/Y H:i:s", strtotime($produto['data_emprestimo'])) . "</td>";
                    echo "<td>
                        <form method='POST' action='consulta-emprestimos.php'>
                            <input type='number' name='quantidade_devolvida' class='input-quantidade' max='" . $produto['quantidade'] . "' min='1' required>
                            <input type='hidden' name='id_emprestimo' value='" . $produto['id_emprestimo'] . "'>
                            <input type='hidden' name='id_produto' value='" . $produto['id_produtos'] . "'>
                            <button type='submit' class='btn'>Devolver</button>
                        </form>
                    </td>";
                    echo "</tr>";
                }
                echo "</tbody></table>";
            }
        } else {
            echo "<p>Nenhum empréstimo encontrado.</p>";
        }
        ?>

        <!-- Botões de navegação -->
        <div class="navigation-buttons">
            <a href="javascript:history.back()" class="btn">Voltar</a>
            <a href="../telainicial.php" class="btn">Tela Inicial</a>
        </div>
    </div>
</body>
</html>
