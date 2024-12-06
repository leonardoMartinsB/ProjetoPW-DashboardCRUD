<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../user/login/login.php");
    exit;
}

include '../config.php';

$id = $_GET['id'];
$query = "SELECT * FROM usuarios WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario_nome = $_POST['usuario']; // Nome do usuário
    $email = $_POST['email'];
    $senha = $_POST['senha']; // Nova senha
    $role = $_POST['role']; // Função do usuário (admin, user, etc.)

    // Iniciar atualização dos parâmetros
    $updates = [];
    $params = [];

    if ($usuario_nome !== $usuario['usuario']) {
        $updates[] = "usuario=?";
        $params[] = $usuario_nome;
    }
    if ($email !== $usuario['email']) {
        $updates[] = "email=?";
        $params[] = $email;
    }
    if (!empty($senha)) {
        // Somente altera a senha se for fornecida
        $updates[] = "senha=?";
        $params[] = password_hash($senha, PASSWORD_DEFAULT); // Criptografar senha
    }
    if ($role !== $usuario['role']) {
        $updates[] = "role=?";
        $params[] = $role;
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        $updates[] = "imagem=?";
        $params[] = $imagem;
    }

    if (!empty($updates)) {
        $updates[] = "modified=NOW()";
        $params[] = $id;
        $query = "UPDATE usuarios SET " . implode(", ", $updates) . " WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param(str_repeat("s", count($params) - 1) . "i", ...$params);

        if ($stmt->execute()) {
            $success = true;
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Club | Editar Usuário</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="../assets/img/gestao.png" type="image/x-icon">
    <link rel="stylesheet" href="../assets/css/clientes/clientes.css">
    <link rel="stylesheet" href="../assets/css/clientes/clienteeditar.css">
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
            <h1 class="mb-4 text-center" id="text">Editar Usuário</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="usuario" class="form-label">Usuário</label>
                        <input type="text" class="form-control" id="usuario" name="usuario"
                            value="<?php echo htmlspecialchars($usuario['usuario']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" placeholder="Nova senha">
                    </div>
                    <div class="col-md-6">
                        <label for="role" class="form-label">Função</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="user" <?php echo $usuario['role'] === 'user' ? 'selected' : ''; ?>>Usuário
                            </option>
                            <option value="admin" <?php echo $usuario['role'] === 'admin' ? 'selected' : ''; ?>>
                                Administrador</option>
                        </select>
                    </div>
                </div>

                <div class="preview-container mt-3">
                    <?php if ($usuario['imagem']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($usuario['imagem']); ?>"
                            class="preview-img" alt="Imagem do Usuário">
                    <?php endif; ?>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="imagem" class="form-label">Imagem do Usuário</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary"
                        onclick="window.location.href='users.php'">Voltar</button>
                    <button type="submit" name="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
            <?php if ($success): ?>
                <div class="alert alert-success mt-3">Usuário atualizado com sucesso!</div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/scripts/darkmode.js"></script>

    <script src="../assets/scripts/clientes/clienteeditar.js"></script>

</body>

</html>