<?php
include_once("conexao.php");

$loginn = $_POST['loginn'];
$senha = $_POST['senha'];

$vericad = "SELECT * FROM credenciais WHERE loginn ='$loginn' AND senha ='$senha'";
$resultado = mysqli_query($conexao, $vericad);

if (mysqli_num_rows($resultado) > 0) {
    $mensagem = "Erro ao cadastrar usuário, pois já existe outro usuário com essas mesmas credenciais.";

} else {
    $cadastro = "INSERT INTO credenciais (loginn, senha) VALUES ('$loginn', '$senha')";
    if (mysqli_query($conexao, $cadastro)) {
        $mensagem = "Usuário cadastrado com sucesso.";
    } else {
        $mensagem = "Erro ao cadastrar usuário.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Cadastro</title>
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Estilo do corpo */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f3f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* Contêiner principal */
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        /* Título */
        h2 {
            font-size: 1.8em;
            color: #2d3436;
            margin-bottom: 20px;
        }

        /* Estilo da mensagem */
        .message {
            font-size: 1.2em;
            color: #0984e3;
            margin-bottom: 20px;
        }

        /* Estilo do botão voltar */
        .back-button {
            padding: 10px 20px;
            font-size: 1.1em;
            font-weight: bold;
            color: #ffffff;
            background-color: #d63031;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }

        /* Efeito hover no botão voltar */
        .back-button:hover {
            background-color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Resultado do Cadastro</h2>
        <div class="message">
            <?php echo $mensagem; ?>
        </div>
        <!-- Botão de Voltar -->
        <a href="cad-user.php" class="back-button">Voltar</a>
    </div>
</body>
</html>

