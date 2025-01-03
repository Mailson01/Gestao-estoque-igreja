
<?php

require __DIR__.'/../verificar-login.php';

include_once "./conexao.php";

// Se o usuário estiver logado, redireciona para a tela inicial


if (isset($_POST['loginn']) && isset($_POST['senha'])){
$loginn = $_POST['loginn'];
$senha = $_POST['senha'];
}

$veri = "SELECT * FROM credenciais WHERE loginn = '$loginn' AND senha ='$senha'";
$resultado = mysqli_query($conexao, $veri);

if (mysqli_num_rows($resultado) > 0) {
    header('Location: /igrejav3/telainicial.php');
    exit();
} else {
    echo "<script>
    alert('Login ou Senha Incorreto');
    window.location.href = 'login.php';
</script>";
exit();

}
