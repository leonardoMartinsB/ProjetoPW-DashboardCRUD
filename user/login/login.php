<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "../../config.php";

    $response = ['success' => false, 'message' => ''];

    try {
        $user = trim($_POST['user']);
        $password = trim($_POST['password']);

        if (empty($user) || empty($password)) {
            $response['message'] = 'Usuário e senha são obrigatórios.';
            echo json_encode($response);
            exit;
        }

        $stmt = $conn->prepare("SELECT id, usuario, senha, role FROM usuarios WHERE usuario = ?");
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user_data = $result->fetch_assoc();


            if (password_verify($password, $user_data['senha'])) {
                $_SESSION['usuario_id'] = $user_data['id'];
                $_SESSION['usuario_nome'] = $user_data['usuario'];
                $_SESSION['usuario_role'] = $user_data['role'];

                $response['success'] = true;
                $response['message'] = 'Login realizado com sucesso!';
                $response['redirect'] = '../../home.php';
            } else {
                $response['message'] = 'Senha incorreta.';

                $response['debug'] = [
                    'hash_armazenado' => $user_data['senha'],
                    'senha_fornecida' => $password,
                ];
            }
        } else {
            $response['message'] = 'Usuário não encontrado.';
        }

        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        $response['message'] = 'Erro no servidor: ' . $e->getMessage();
        error_log('Erro no login: ' . $e->getMessage());
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

    <link rel="stylesheet" href="../style/login.css">

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
    <div class="login-container">
        <div class="login-box">
            <div class="logo">
                <a href="../../index.php">
                    <span class="garage">Garage</span>
                    <span class="club">Club</span>
                </a>
            </div>
            <div id="alert-container"></div>
            <form id="login-form">
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="text" id="user" name="user" placeholder="Usuário" required>
                </div>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Senha" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                <button type="submit" class="login-btn">Login</button>
            </form>
            <p class="signup-text">Ainda não tem conta? <a href="../../user/register/register.php">Crie Agora</a></p>
        </div>
    </div>

    <div class="loader" id="loader">
        <div class="spinner"></div>
        <div class="loading-text" id="loading-text">Entrando no sistema...</div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
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

        document.getElementById('login-form').addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const loader = document.getElementById('loader');
            const alertContainer = document.getElementById('alert-container');

            try {

                loader.style.display = 'flex';

                const response = await fetch('login.php', {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Erro na comunicação com o servidor.');
                }

                const result = await response.json();

                if (result.success) {

                    alertContainer.innerHTML = `
                <div class="alert alert-success" role="alert">
                    ${result.message}
                </div>`;
                    setTimeout(() => {
                        window.location.href = result.redirect;
                    }, 3000);
                } else {

                    loader.style.display = 'none';
                    alertContainer.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    ${result.message}
                </div>`;
                }
            } catch (error) {
                console.error(error);
                loader.style.display = 'none';
                alertContainer.innerHTML = `
            <div class="alert alert-danger" role="alert">
                Ocorreu um erro inesperado. Por favor, tente novamente mais tarde.
            </div>`;
            }
        });

    </script>
</body>

</html>