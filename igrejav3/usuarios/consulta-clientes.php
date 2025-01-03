<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultar Clientes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color:#5c3d15;
            color: white;
            font-size: 14px;
        }

        table td {
            font-size: 12px;
        }

        .back-btn {
            display: inline-block;
            padding: 5px 10px;
            background-color: #4CAF50;
            color: black;
            text-decoration: none;
            border-radius: 4px;
            font-size: 15px;
            text-align: center;
            margin-top: 20px;
            display: block;
            width: 200px;
            margin-left: auto;
            margin-right: auto;
            font-weight: bold;
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        .edit-btn, .delete-btn {
            background-color: #f39c12;
            color: white;
            padding: 4px 8px;
            border: none;
            border-radius: 4px;
            font-size: 12px;
            cursor: pointer;
            margin-right: 5px;
        }

        .edit-btn:hover {
            background-color: #e67e22;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .delete-btn:hover {
            background-color: #c0392b;
        }

        /* Media Queries para Responsividade */

        /* Para telas pequenas (celulares) */
        @media (max-width: 600px) {
            h1 {
                font-size: 1.6em;
            }

            table th, table td {
                font-size: 10px;
                padding: 8px;
            }

            .back-btn {
                width: 20%;
                font-size: 14px;
                padding: 10px;
            }

            .edit-btn, .delete-btn {
                font-size: 6px;
                padding: 5px 5px;
            }
        }

        /* Para tablets (largura entre 601px e 1024px) */
        @media (min-width: 601px) and (max-width: 1024px) {
            h1 {
                font-size: 1.8em;
            }

            table th, table td {
                font-size: 12px;
                padding: 9px;
            }

            .back-btn {
                width: 100%;
                font-size: 14px;
                padding: 8px;
            }

            .edit-btn, .delete-btn {
                font-size: 11px;
                padding: 6px 12px;
            }
        }

        /* Para telas grandes (desktop) */
        @media (min-width: 1025px) {
            h1 {
                font-size: 2em;
            }

            table th, table td {
                font-size: 14px;
                padding: 10px;
            }

            .back-btn {
                width: 200px;
                font-size: 12px;
                padding: 5px 10px;
            }

            .edit-btn, .delete-btn {
                font-size: 12px;
                padding: 4px 8px;
            }
        }
    </style>
</head>
<body>

    <h1>Consultar Clientes</h1>

    <?php
        // Conectar ao banco de dados
        include_once "./conexao.php";

        // Verificar se foi passado um ID para exclusão e se é um número válido
        if (isset($_GET['delete_id']) && is_numeric($_GET['delete_id'])) {
            $delete_id = (int) $_GET['delete_id']; // Converte para inteiro

            // Excluir o cliente do banco de dados
            $sql_delete = "DELETE FROM clientes WHERE id_cliente = $delete_id";

            if (mysqli_query($conexao, $sql_delete)) {
                echo "<div class='resultado sucesso'>Cliente excluído com sucesso!</div>";
            } else {
                echo "<div class='resultado erro'>Erro ao excluir cliente: " . mysqli_error($conexao) . "</div>";
            }
        }

        // Consulta para obter todos os clientes
        $sql = "SELECT * FROM clientes";
        $result = mysqli_query($conexao, $sql);

        // Verificar se a consulta foi bem-sucedida
        if (!$result) {
            echo "<div class='resultado erro'>Erro na consulta: " . mysqli_error($conexao) . "</div>";
        } else {
            if (mysqli_num_rows($result) > 0) {
                echo "<table>
                        <tr>
                            <th>Nome</th>
                            <th>Telefone</th>
                            <th>Ações</th>
                        </tr>";

                // Exibe todos os clientes cadastrados
                while ($row = mysqli_fetch_assoc($result)) {
                    // Verifica se o campo 'id_cliente' existe
                    if (isset($row['id_cliente'])) {
                        echo "<tr>
                                <td>" . $row['nome'] . "</td>
                                <td>" . $row['telefone'] . "</td>
                                <td>
                                    <a href='editar-cliente.php?id_cliente=" . $row['id_cliente'] . "' class='edit-btn'>Editar</a>
                                    <a href='consulta-clientes.php?delete_id=" . $row['id_cliente'] . "' class='delete-btn' onclick='return confirm(\"Tem certeza que deseja excluir este cliente?\")'>Excluir</a>
                                </td>
                            </tr>";
                    } else {
                        echo "<tr><td colspan='3'>Erro: ID não encontrado.</td></tr>";
                    }
                }

                echo "</table>";
            } else {
                echo "<div class='resultado erro'>Nenhum cliente encontrado.</div>";
            }
        }

        // Fechar a conexão
        mysqli_close($conexao);
    ?>

    <a href="cad-clientes.php" class="back-btn">Voltar</a>

</body>
</html>
