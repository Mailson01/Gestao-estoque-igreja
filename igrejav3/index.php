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
            padding: 10px;
        }

        /* Contêiner principal */
        .container {
            background-color: #ffffff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 900px;
            text-align: center;
        }

        /* Contêiner para a imagem de boas-vindas */
        .image-container {
            width: 100%;
            margin-bottom: 20px;
        }

        /* Estilo da imagem de boas-vindas */
        .welcome-image {
            width: 100%;
            height: auto;
            object-fit: contain;
            border-radius: 10px;
            border: 3px solid #f0c420;
        }

        /* Título */
        p {
            font-size: 1.8em;
            font-weight: 600;
            color: #5c3d15;
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
            background-color: #5c3d15;
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
            background-color: #a67845;
            transform: scale(1.05);
        }

        /* Botão de sair */
        .exit-button {
            background-color: #b71c1c;
        }

        .exit-button:hover {
            background-color: #8a0000;
        }

        /* Responsividade para tablets */
        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }

            p {
                font-size: 1.5em;
            }

            button {
                font-size: 0.9em;
            }
        }

        /* Responsividade para celulares */
        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }

            p {
                font-size: 1.2em;
            }

            button {
                padding: 10px;
                font-size: 0.8em;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Contêiner da imagem -->
        <div class="image-container">
            <img src="/imgs/logo-horizontal.png" alt="Bem-vindo" class="welcome-image">
        </div>
        <p>Seja Bem-vindo(a) à Gestão da Igreja</p>

        <!-- Contêiner para os botões -->
        <div class="button-container">
            <form action="formcadprod.php">
                <button type="submit">Cadastrar Produtos</button>
            </form>
            <form action="consultaestoque.php" method="post">
                <button type="submit">Consultar Estoque</button>
            </form>
            <form action="cadastroclientes.php">
                <button type="submit">Cadastrar Clientes</button>
            </form>
            <form action="cademprestimos.php">
                <button type="submit">Cad. Empréstimos</button>
            </form>
            <form action="consultaemprestimos.php">
                <button type="submit">Movimentações</button>
            </form>

            <form action="login.php">
                <button type="submit" class="exit-button">Sair</button>
            </form>
        </div>
    </div>
</body>
</html>
