<?php
include '../config.php';

$id = $_GET['id'];
$query = "SELECT * FROM clientes WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$cliente = $result->fetch_assoc();
$stmt->close();

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $cidade = $_POST['cidade'];
    $estado = $_POST['estado'];
    $cpf = $_POST['cpf'];

    $updates = [];
    $params = [];

    if ($nome !== $cliente['nome']) {
        $updates[] = "nome=?";
        $params[] = $nome;
    }
    if ($email !== $cliente['email']) {
        $updates[] = "email=?";
        $params[] = $email;
    }
    if ($telefone !== $cliente['telefone']) {
        $updates[] = "telefone=?";
        $params[] = $telefone;
    }
    if ($endereco !== $cliente['endereco']) {
        $updates[] = "endereco=?";
        $params[] = $endereco;
    }
    if ($cidade !== $cliente['cidade']) {
        $updates[] = "cidade=?";
        $params[] = $cidade;
    }
    if ($estado !== $cliente['estado']) {
        $updates[] = "estado=?";
        $params[] = $estado;
    }
    if ($cpf !== $cliente['cpf']) {
        $updates[] = "cpf=?";
        $params[] = $cpf;
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        $updates[] = "imagem=?";
        $params[] = $imagem;
    }

    if (!empty($updates)) {
        $updates[] = "modified=NOW()";
        $params[] = $id;
        $query = "UPDATE clientes SET " . implode(", ", $updates) . " WHERE id=?";
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
    <title>Garage Club | Editar Moto</title>

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
            <h1 class="mb-4 text-center" id="text">Editar Cliente</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="nome" class="form-label">Nome</label>
                        <input type="text" class="form-control" id="nome" name="nome"
                            value="<?php echo htmlspecialchars($cliente['nome']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email"
                            value="<?php echo htmlspecialchars($cliente['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="telefone" class="form-label">Telefone</label>
                        <input type="text" class="form-control" id="telefone" name="telefone"
                            value="<?php echo htmlspecialchars($cliente['telefone']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="endereco" class="form-label">Endereço</label>
                        <input type="text" class="form-control" id="endereco" name="endereco"
                            value="<?php echo htmlspecialchars($cliente['endereco']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="cidade" class="form-label">Cidade</label>
                        <input type="text" class="form-control" id="cidade" name="cidade"
                            value="<?php echo htmlspecialchars($cliente['cidade']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="estado" class="form-label">Estado</label>
                        <input type="text" class="form-control" id="estado" name="estado"
                            value="<?php echo htmlspecialchars($cliente['estado']); ?>" required maxlength="2">
                    </div>
                    <div class="col-md-6">
                        <label for="cpf" class="form-label">CPF</label>
                        <input type="text" class="form-control" id="cpf" name="cpf"
                            value="<?php echo htmlspecialchars($cliente['cpf']); ?>" required>
                    </div>
                </div>

                <div class="preview-container mt-3">
                    <?php if ($cliente['imagem']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($cliente['imagem']); ?>"
                            class="preview-img" alt="Imagem do Cliente">
                    <?php endif; ?>
                </div>

                <div class="col-md-4 mt-3">
                    <label for="imagem" class="form-label">Imagem do Cliente</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary"
                        onclick="window.location.href='index.php'">Voltar</button>
                    <button type="submit" name="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
            <?php if ($success): ?>
                <div class="alert alert-success mt-3">Cliente atualizado com sucesso!</div>
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