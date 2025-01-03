<?php
include_once "../usuarios/conexao.php";
// Conexão com o banco de dados
$conn = mysqli_connect('localhost', 'root', '', 'igreja');
if (!$conn) {
    die('Erro de conexão: ' . mysqli_connect_error());
}

// Obter clientes e produtos para preencher as opções no formulário
$sqlClientes = "SELECT id_cliente, nome FROM clientes";
$resultClientes = mysqli_query($conn, $sqlClientes);

$sqlProdutos = "SELECT id_produtos, produto FROM produtos";
$resultProdutos = mysqli_query($conn, $sqlProdutos);

// Criar um array de produtos para facilitar o uso no JavaScript
$produtos = [];
while ($produto = mysqli_fetch_assoc($resultProdutos)) {
    $produtos[] = $produto;
}

// Variáveis de mensagem
$mensagem = '';
$mensagemClass = ''; // Para definir a classe de estilo (sucesso ou erro)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Coletar dados do formulário
    $id_cliente = $_POST['id_cliente'];
    $produtosSelecionados = $_POST['id_produto'];
    $quantidades = $_POST['quantidade'];

    // Verificar se o cliente foi selecionado e produtos foram escolhidos
    if ($id_cliente && !empty($produtosSelecionados)) {
        // Iniciar a transação para garantir consistência
        mysqli_begin_transaction($conn);

        try {
            // Inserir o empréstimo principal
            $sqlEmprestimo = "INSERT INTO emprestimos (id_cliente, data_emprestimo) VALUES ('$id_cliente', NOW())";
            $resultEmprestimo = mysqli_query($conn, $sqlEmprestimo);

            if (!$resultEmprestimo) {
                throw new Exception("Erro ao cadastrar o empréstimo principal: " . mysqli_error($conn));
            }

            // Obter o ID do empréstimo inserido
            $id_emprestimo = mysqli_insert_id($conn);

            // Inserir os produtos no empréstimo e atualizar o estoque
            foreach ($produtosSelecionados as $index => $id_produto) {
                $quantidade = $quantidades[$index];

                // Inserir o produto no empréstimo
                $sqlItem = "INSERT INTO emprestimos_produtos (id_emprestimo, id_produto, quantidade) 
                            VALUES ('$id_emprestimo', '$id_produto', '$quantidade')";
                $resultItem = mysqli_query($conn, $sqlItem);

                if (!$resultItem) {
                    throw new Exception("Erro ao cadastrar produto no empréstimo: " . mysqli_error($conn));
                }

                // Atualizar o estoque, subtraindo a quantidade emprestada
                $sqlEstoque = "UPDATE estoque 
                               SET quantidade_total = quantidade_total - $quantidade 
                               WHERE id_produtos = '$id_produto' AND quantidade_total >= $quantidade";
                $resultEstoque = mysqli_query($conn, $sqlEstoque);

                if (!$resultEstoque) {
                    throw new Exception("Erro ao atualizar o estoque: " . mysqli_error($conn));
                }

                // Verificar se o estoque foi suficiente
                if (mysqli_affected_rows($conn) == 0) {
                    throw new Exception("Estoque insuficiente para o produto $id_produto.");
                }
            }

            // Commit da transação
            mysqli_commit($conn);

            // Mensagem de sucesso
            $mensagem = "Empréstimo cadastrado com sucesso!";
            $mensagemClass = 'success';
        } catch (Exception $e) {
            // Rollback da transação em caso de erro
            mysqli_rollback($conn);

            // Mensagem de erro
            $mensagem = "Erro ao cadastrar o empréstimo: " . $e->getMessage();
            $mensagemClass = 'alert';
        }
    } else {
        $mensagem = "Por favor, preencha todos os campos corretamente.";
        $mensagemClass = 'alert';
    }
}

// Fechar a conexão com o banco de dados
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Empréstimo</title>
    <img src="../assets/imgs/logo-horizontal.png" alt="logo" class="header-img">
    <style>
        /* Estilos Globais */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            font-size: 14px; /* Ajuste do tamanho da fonte */
        }

        /* Estilo para a imagem na parte superior */
        .header-img {
            width: 25%;
            height: auto;
            display: block;
            margin: 10px auto;
        }

        /* Estilos do cabeçalho */
        header {
            background-color: #5c3d15;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-bottom: 15px;
        }

        h1 {
            font-size: 18px;
            margin: 0;
            color: white;
        }

        /* Container do formulário */
        .form-container {
            display: flex;
            justify-content: center;
            padding: 10px;
        }

        form {
            background-color: #fff;
            padding: 15px;
            width: 100%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        /* Estilo dos labels */
        label {
            font-size: 14px;
            margin-bottom: 8px;
            color: #333;
        }

        /* Estilo dos campos de entrada */
        select, input[type="number"], button {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        /* Estilo para os botões */
        button {
            background-color: green;
            color: white;
            cursor: pointer;
            font-size: 14px;
            border: none;
            padding: 8px;
            margin-top: 5px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #45a049;
        }

        /* Mensagens de Sucesso e Erro */
        .alert, .success {
            font-weight: bold;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .alert {
            color: red;
            background-color: #fdd;
        }

        .success {
            color: green;
            background-color: #dfd;
        }

        /* Estilo para os produtos */
        .produto-item {
            padding: 10px;
            background-color: #f9f9f9;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        /* Estilo para os campos de produtos */
        .produto-item label {
            font-size: 14px;
        }

        /* Botão de Adicionar Produto */
        #addProduto {
            background-color: #5c3d15;
            color: white;
            font-size: 14px;
            padding: 8px;
            cursor: pointer;
            border: 1px solid #ccc;
        }

        #addProduto:hover {
            background-color: #45a049;
        }

        /* Botão de Voltar */
        #voltar {
            background-color: red;
            color: white;
            font-size: 14px;
            border: 1px solid #bbb;
            padding: 8px;
            cursor: pointer;
            width: 100%;
            margin-top: 10px;
            text-align: center;
        }

        #voltar:hover {
            background-color: #45a049;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            /* Ajustes para dispositivos móveis */
            body {
                font-size: 12px;
            }

            .form-container {
                padding: 10px;
            }

            form {
                padding: 10px;
                max-width: 100%;
            }

            .header-img {
                width: 50%; /* Aumenta a imagem em telas menores */
            }

            h1 {
                font-size: 16px;
            }

            label, select, input[type="number"], button {
                font-size: 12px; /* Ajuste da fonte para telas menores */
            }

            #addProduto, #voltar {
                font-size: 12px;
                padding: 6px;
            }
        }
    </style>
</head>
<body>
    <header>
        <h1>Cadastro de Empréstimo de Produto</h1>
    </header>

    <div class="form-container">
        <form action="" method="post">
            <!-- Exibe a mensagem de sucesso ou erro -->
            <?php if ($mensagem) { ?>
                <p class="<?php echo $mensagemClass; ?>"><?php echo $mensagem; ?></p>
            <?php } ?>

            <label for="id_cliente">Cliente:</label>
            <select name="id_cliente" required>
                <option value="">Selecione um Cliente</option>
                <?php while ($cliente = mysqli_fetch_assoc($resultClientes)) { ?>
                    <option value="<?php echo $cliente['id_cliente']; ?>"><?php echo $cliente['nome']; ?></option>
                <?php } ?>
            </select>

            <div id="produtos">
                <div class="produto-item">
                    <label for="id_produto">Produto:</label>
                    <select name="id_produto[]" required>
                        <option value="">Selecione um Produto</option>
                        <?php foreach ($produtos as $produto) { ?>
                            <option value="<?php echo $produto['id_produtos']; ?>"><?php echo $produto['produto']; ?></option>
                        <?php } ?>
                    </select>

                    <label for="quantidade">Quantidade:</label>
                    <input type="number" name="quantidade[]" min="1" required>
                </div>
            </div>

            <button type="button" id="addProduto">Adicionar Outro Produto</button><br><br>

            <button type="submit">Cadastrar Empréstimo</button>
            <button type="button" id="voltar" onclick="window.location.href='../telainicial.php';">Voltar</button>
        </form>
    </div>

    <script>
        document.getElementById('addProduto').addEventListener('click', function() {
            // Cria uma nova linha de produto
            var produtoItem = document.createElement('div');
            produtoItem.classList.add('produto-item');
            
            produtoItem.innerHTML = `
                <label for="id_produto">Produto:</label>
                <select name="id_produto[]" required>
                    <option value="">Selecione um Produto</option>
                    <?php foreach ($produtos as $produto) { ?>
                        <option value="<?php echo $produto['id_produtos']; ?>"><?php echo $produto['produto']; ?></option>
                    <?php } ?>
                </select>

                <label for="quantidade">Quantidade:</label>
                <input type="number" name="quantidade[]" min="1" required>
            `;
            document.getElementById('produtos').appendChild(produtoItem);
        });
    </script>
</body>
</html>
