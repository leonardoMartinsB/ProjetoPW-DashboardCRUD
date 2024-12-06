<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

include '../config.php';

if (isset($_POST['submit'])) {
    $usuario = $conn->real_escape_string($_POST['usuario']);
    $email = $conn->real_escape_string($_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $role = 'user';

    $checkUsuario = "SELECT * FROM usuarios WHERE usuario = '$usuario' OR email = '$email'";
    $result = $conn->query($checkUsuario);

    if ($result->num_rows > 0) {
        $mensagemErro = "Erro: O nome de usuário ou e-mail já está em uso. Escolha outro.";
    } else {
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
            $imagem = addslashes(file_get_contents($_FILES['imagem']['tmp_name']));
        } else {
            $imagem = null;
        }

        $sql = "INSERT INTO usuarios (usuario, email, senha, imagem, role, created, modified) 
                VALUES ('$usuario', '$email', '$senha', '$imagem', '$role', NOW(), NOW())";

        if ($conn->query($sql) === TRUE) {
            $mensagemSucesso = "Usuário registrado com sucesso!";
        } else {
            $mensagemErro = "Erro ao registrar usuário: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Club | Adicionar Usuário</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/gestao.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/clientes/clientes.css">
    <link rel="stylesheet" href="../assets/css/clientes/clienteadd.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../home.php">
                <span class="brand-garage">Garage</span>
                <span class="brand-club">Club</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="../home.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Configurações</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Relatórios</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">Gestão</a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="../clientes/index.php"><i class="fa-solid fa-users"></i>
                                    Ver Usuários</a></li>
                            <li><a class="dropdown-item" href="../motos/index.php"> <i
                                        class="fa-solid fa-motorcycle"></i> Ver Motos</a></li>
                        </ul>
                    </li>
                </ul>
                <label class="switch">
                    <input type="checkbox" id="theme-toggle">
                    <span class="slider">
                        <i class="fa fa-sun"></i>
                        <i class="fa fa-moon"></i>
                    </span>
                </label>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="form-background">
            <h1 class="mb-4 text-center" id="text">Adicionar Novo Usuário</h1>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="usuario" class="form-label">Nome de Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                            placeholder="Nome de usuário" required>
                    </div>
                    <div class="col-md-4">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="E-mail do usuário"
                            required>
                    </div>
                    <div class="col-md-4 position-relative">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha"
                            placeholder="Senha do usuário" required>
                        <i class="fa-solid fa-eye" id="toggle-password"
                            style="position: absolute; top: 60%; right: 30px; cursor: pointer; color:black;"></i>
                    </div>
                    <div class="col-md-4">
                        <label for="imagem" class="form-label">Foto do Usuário</label>
                        <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                    </div>
                </div>
                <div class="preview-container" id="preview-container"></div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                    <button type="reset" class="btn btn-secondary" id="clear-button">Limpar Campos</button>
                    <button type="submit" name="submit" class="btn btn-primary">Adicionar Usuário</button>
                </div>
            </form>
            <?php if (isset($mensagemErro)): ?>
                <div id="errorAlert" class="alert alert-danger" role="alert">
                    <?php echo $mensagemErro; ?>
                </div>
            <?php endif; ?>
            <div id="successAlert" class="alert alert-success" role="alert">
                <?php echo $mensagemSucesso; ?>
            </div>
        </div>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/scripts/darkmode.js"></script>
    <script src="../assets/scripts/clientes/clienteadd.js"></script>

    <script>
        document.getElementById('imagem').addEventListener('change', function (e) {
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';

            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const img = document.createElement('img');
                    img.src = event.target.result;
                    img.alt = 'Preview da imagem';
                    img.classList.add('img-fluid', 'mt-2');
                    img.style.maxWidth = '150px';
                    img.style.maxHeight = '150px';
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });

        const togglePassword = document.getElementById('toggle-password');
        const senhaInput = document.getElementById('senha');

        togglePassword.addEventListener('click', function () {
            const type = senhaInput.type === 'password' ? 'text' : 'password';
            senhaInput.type = type;

            this.classList.toggle('fa-eye-slash');
        });

        document.addEventListener("DOMContentLoaded", function () {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');

            <?php if (!empty($mensagemSucesso)): ?>
                successAlert.style.display = 'block';
                setTimeout(() => {
                    successAlert.style.opacity = '0';
                    setTimeout(() => { successAlert.style.display = 'none'; }, 500);
                }, 4000);
            <?php endif; ?>

            <?php if (!empty($mensagemErro)): ?>
                errorAlert.style.display = 'block';
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    setTimeout(() => { errorAlert.style.display = 'none'; }, 500);
                }, 4000);
            <?php endif; ?>
        });
    </script>

</body>

</html>