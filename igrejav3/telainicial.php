<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('Location: /resources/utils/usuarios/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tela Inicial - Sistema de Gestão da Igreja</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@500&family=Roboto:wght@300&display=swap" rel="stylesheet">
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo do corpo */
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #f2f2f2, #d7e2e8);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            color: #333;
        }

        /* Contêiner principal */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 900px;
            text-align: center;
        }

        /* Contêiner para a imagem de boas-vindas */
        .image-container {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
            overflow: hidden; /* Garante que a imagem não ultrapasse os limites */
        }

        /* Estilo da imagem de boas-vindas */
        .welcome-image {
            width: 100%;
            height: auto;
            object-fit: contain; /* Faz a imagem ajustar ao tamanho do contêiner mantendo a proporção */
            border-radius: 10px;
            border: 3px solid #f0c420; /* Cor dourada */
        }

        /* Título */
        p {
            font-size: 1.8em;
            font-weight: 600;
            color: #5c3d15; /* Tom de marrom */
            margin-bottom: 30px;
        }

        /* Contêiner para os botões */
        .button-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        /* Estilo dos botões */
        button {
            background-color: #5c3d15; /* Cor marrom para botões */
            color: #fff;
            padding: 12px 24px;
            font-size: 1em;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            width: 100%;
            max-width: 220px;
        }

        /* Efeito ao passar o mouse */
        button:hover {
            background-color: #a67845; /* Cor mais clara no hover */
            transform: scale(1.05);
        }

        /* Botão de sair */
        .exit-button {
            background-color: #b71c1c; /* Vermelho para sair */
        }

        .exit-button:hover {
            background-color: #8a0000; /* Tom mais escuro de vermelho */
        }

        /* Responsividade para telas menores */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            /* Botões ocupando o máximo possível de largura em dispositivos móveis */
            button {
                width: 100%;
                max-width: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Contêiner da imagem -->
        <div class="image-container">
            <img src="../igrejav3/assets/imgs/logo-horizontal.png" alt="Bem-vindo" class="welcome-image">
        </div>
        <p>Seja Bem-vindo(a) à Gestão da Igreja</p>

        <!-- Contêiner para os botões -->
        <div class="button-container">
            <form action="produtos\form-cad-prod.php">
                <button type="submit">Cadastrar Produtos</button>
            </form>
            <form action="produtos/consulta-estoque.php" method="post">
                <button type="submit">Consultar Estoque</button>
            </form>
            <form action="usuarios\cad-clientes.php">
                <button type="submit">Cadastrar Clientes</button>
            </form>
            <form action="./produtos/cad-emprestimos.php">
                <button type="submit">Cad. Empréstimos</button>
            </form>
            <form action="./produtos/consulta-emprestimos.php">
                <button type="submit">Movimentações</button>
            </form>
            </form>
        <form action="../igrejav3/usuarios/cad-user.php" method="get">
            <button class="button register-button" type="submit">Cadastrar Usuário</button>
        </form>
            <form action="../igrejav3/usuarios/login.php">
                <button type="submit" class="exit-button">Sair</button>
            </form>
            
        </div>
    </div>
</body>
</html>
