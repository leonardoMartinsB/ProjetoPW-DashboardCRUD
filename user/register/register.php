<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "../../config.php";

    $response = ['success' => false, 'message' => ''];

    try {
        $usuario = trim($_POST['name']);
        $email = trim($_POST['email']);
        $senha = password_hash(trim($_POST['password']), PASSWORD_BCRYPT);

        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $response['message'] = 'Este email já está em uso.';
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("INSERT INTO usuarios (usuario, email, senha) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $usuario, $email, $senha);

        if ($stmt->execute()) {

            $_SESSION['usuario_id'] = $stmt->insert_id;
            $_SESSION['usuario_nome'] = $usuario;

            $response['success'] = true;
            $response['message'] = 'Registro concluído com sucesso!';
            $response['redirect'] = '../../home.php';
        } else {
            $response['message'] = 'Erro ao registrar usuário.';
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $response['message'] = 'Erro no servidor: ' . $e->getMessage();
    }

    echo json_encode($response);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../../assets/img/gestao.png" type="image/x-icon">

    <link rel="stylesheet" href="../style/register.css">

    <style>
        .loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .loader .spinner {
            border: 8px solid #f3f3f3;
            border-top: 8px solid #c1121f;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        .loader .loading-text {
            font-size: 20px;
            color: #c1121f;
            margin-top: 20px;
            font-weight: bold;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <title>Garage Club | Dashboard | CRUD-PW2</title>
</head>

<body>
    <div class="register-box">
        <div class="logo">
            <a href="../../index.php">
                <span class="garage">Garage</span>
                <span class="club">Club</span>
            </a>
        </div>
        <div id="alert-container"></div>

        <form id="register-form">
            <div class="input-group">
                <i class="fas fa-user"></i>
                <input type="text" name="name" id="name" placeholder="Usuário" required>
            </div>
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" id="register-email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class="fas fa-lock"></i>
                <input type="password" name="password" id="register-password" placeholder="Senha" required>
                <span class="toggle-password" onclick="togglePassword()">
                    <i class="fas fa-eye"></i>
                </span>
            </div>

            <button type="submit" class="register-btn">Registrar</button>
        </form>

        <p class="signin-text">Já tem uma conta? <a href="../../user/login/login.php">Faça Login</a></p>
    </div>

    <div class="loader" id="loader" style="display: none;">
        <div class="spinner"></div>
        <div class="loading-text" id="loading-text">Registrando...</div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('register-password');
            const toggleIcon = document.querySelector('.toggle-password i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        document.getElementById('register-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const loader = document.getElementById('loader');
            const alertContainer = document.getElementById('alert-container');

            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Erro na comunicação com o servidor.');
                }

                const result = await response.json();

                alertContainer.innerHTML = `<div class="alert alert-${result.success ? 'success' : 'danger'}" role="alert">
                    ${result.message}
                </div>`;
                if (result.success) {

                    loader.style.display = 'flex';
                    document.getElementById('loading-text').textContent = "Registrando...";

                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 3000);
                }
            } catch (error) {
                console.error(error);
                alertContainer.innerHTML = `<div class="alert alert-danger" role="alert">
                    Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.
                </div>`;
            }
        });
    </script>
</body>

</html>