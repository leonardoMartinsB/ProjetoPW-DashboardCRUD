<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../user/login/login.php");
    exit;
}

include '../config.php';

$id = $_GET['id'];
$query = "SELECT * FROM motos_dados WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$moto = $result->fetch_assoc();
$stmt->close();

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $motor = intval(str_replace(' CC', '', $_POST['motor']));
    $ano = intval($_POST['ano']);
    $cor = $_POST['cor'];
    $quilometragem = intval(str_replace('.', '', $_POST['quilometragem']));

    $updates = [];
    $params = [];

    if ($marca !== $moto['marca']) {
        $updates[] = "marca=?";
        $params[] = $marca;
    }
    if ($modelo !== $moto['modelo']) {
        $updates[] = "modelo=?";
        $params[] = $modelo;
    }
    if ($motor !== $moto['motor']) {
        $updates[] = "motor=?";
        $params[] = $motor;
    }
    if ($ano !== $moto['ano']) {
        $updates[] = "ano=?";
        $params[] = $ano;
    }
    if ($cor !== $moto['cor']) {
        $updates[] = "cor=?";
        $params[] = $cor;
    }
    if ($quilometragem !== $moto['quilometragem']) {
        $updates[] = "quilometragem=?";
        $params[] = $quilometragem;
    }

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) {
        $imagem = file_get_contents($_FILES['imagem']['tmp_name']);
        $updates[] = "imagem=?";
        $params[] = $imagem;
    }

    if (!empty($updates)) {
        $updates[] = "modified=NOW()";
        $params[] = $id;
        $query = "UPDATE motos_dados SET " . implode(", ", $updates) . " WHERE id=?";
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

    <link rel="stylesheet" href="../assets/css/motos/motos.css">

    <link rel="stylesheet" href="../assets/css/motos/motoeditar.css">
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
            <h1 class="mb-4 text-center" id="text">Editar Moto</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca"
                            value="<?php echo htmlspecialchars($moto['marca']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo"
                            value="<?php echo htmlspecialchars($moto['modelo']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="motor" class="form-label">Motor (CC)</label>
                        <input type="text" class="form-control" id="motor" name="motor"
                            value="<?php echo htmlspecialchars($moto['motor']) . ' CC'; ?>" required maxlength="7">
                    </div>
                    <div class="col-md-4">
                        <label for="ano" class="form-label">Ano</label>
                        <input type="number" class="form-control" id="ano" name="ano"
                            value="<?php echo htmlspecialchars($moto['ano']); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" class="form-control" id="cor" name="cor"
                            value="<?php echo htmlspecialchars($moto['cor']); ?>" required maxlength="8"
                            pattern="^[A-Za-z]+$" title="Apenas letras, máximo 8 caracteres.">
                    </div>
                    <div class="col-md-4">
                        <label for="quilometragem" class="form-label">Quilometragem</label>
                        <input type="text" class="form-control" id="quilometragem" name="quilometragem"
                            value="<?php echo htmlspecialchars(number_format($moto['quilometragem'], 0, '.', '.')) . ' KM'; ?>"
                            required>
                    </div>
                </div>
                <div class="preview-container mt-3">
                    <?php if ($moto['imagem']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($moto['imagem']); ?>" class="preview-img"
                            style="max-width: 200px; border-radius: 8px; border: 1px solid #ced4da;" alt="Imagem da Moto">
                    <?php endif; ?>
                </div>
                <div class="col-md-4 mt-3">
                    <label for="imagem" class="form-label">Imagem da Moto</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                </div>

                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary"
                        onclick="window.location.href='index.php'">Voltar</button>
                    <button type="submit" name="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
            <?php if ($success): ?>
                <div class="alert alert-success mt-3">Moto atualizada com sucesso!</div>
            <?php endif; ?>
        </div>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/scripts/darkmode.js"></script>

    <script src="../assets/scripts/motos/motoeditar.js"></script>

</body>

</html>