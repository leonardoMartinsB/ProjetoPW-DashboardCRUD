<?php

session_start();
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../user/login/login.php");
    exit;
}

ob_start();
require '../config.php';

$success = isset($_GET['success']) && $_GET['success'] === 'true';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $motor = $_POST['motor'];
    $ano = $_POST['ano'];
    $cor = $_POST['cor'];

    $quilometragem = preg_replace('/\D/', '', $_POST['quilometragem']);
    $imagem = null;

    if (!empty($_FILES["imagem"]["tmp_name"])) {
        $imagem = addslashes(file_get_contents($_FILES["imagem"]["tmp_name"]));
    }

    if (!preg_match('/^\d{4}$/', $ano)) {
        die("Ano deve ter exatamente 4 dígitos.");
    }

    if (!preg_match('/^[A-Za-z]{1,8}$/', $cor)) {
        die("Cor deve conter apenas letras (sem números) e ter no máximo 8 caracteres.");
    }

    $sql = "INSERT INTO motos_dados (marca, modelo, motor, ano, cor, quilometragem, imagem, created, modified)
            VALUES ('$marca', '$modelo', '$motor', '$ano', '$cor', '$quilometragem', '$imagem', NOW(), NOW())";

    if ($conn->query($sql) === TRUE) {
        header("Location: motoadd.php?success=true");
        exit();
    } else {
        echo "<div class='alert alert-danger'>Erro: " . $conn->error . "</div>";
    }

    $conn->close();
}

ob_end_flush();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Club | Adicionar Moto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="../assets/img/gestao.png" type="image/x-icon">

    <link rel="stylesheet" href="../assets/css/motos/motos.css">

    <link rel="stylesheet" href="../assets/css/motos/motoadd.css">

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
                                        class="fa-solid fa-motorcycle"></i> Ver
                                    Motos</a></li>
                            <li><a class="dropdown-item" href="../clientes/index.php"><i class="fa-solid fa-users"></i>
                                    ver Clientes</a></li>
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
            <h1 class="mb-4 text-center" id="text">Adicionar Nova Moto</h1>
            <form action="motoadd.php" method="POST" enctype="multipart/form-data">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="marca" class="form-label">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" placeholder="Ex: Yamaha"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label for="modelo" class="form-label">Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" placeholder="Ex: XRE 300"
                            required>
                    </div>
                    <div class="col-md-4">
                        <label for="motor" class="form-label">Motor (CC)</label>
                        <input type="text" class="form-control" id="motor" name="motor" placeholder="Ex: 300CC" required
                            maxlength="7">
                        <small class="form-text text-muted">Apenas números (máximo 4 dígitos)</small>
                    </div>
                    <div class="col-md-4">
                        <label for="ano" class="form-label">Ano</label>
                        <input type="number" class="form-control" id="ano" name="ano"
                            placeholder="Ano de fabricação da moto" required>
                    </div>
                    <div class="col-md-4">
                        <label for="cor" class="form-label">Cor</label>
                        <input type="text" class="form-control" id="cor" name="cor" placeholder="Cor da Moto" required
                            maxlength="8" pattern="^[A-Za-z]+$" title="Apenas letras, máximo 8 caracteres.">
                        <small class="form-text text-muted">Apenas letras, máximo 8 caracteres.</small>
                    </div>
                    <div class="col-md-4">
                        <label for="quilometragem" class="form-label">Quilometragem</label>
                        <input type="text" class="form-control" id="quilometragem" name="quilometragem"
                            placeholder="Ex: 15.000KM" required>
                        <small class="form-text text-muted">Somente números, até 1.000.000 KM</small>
                    </div>
                    <div class="col-md-4">
                        <label for="imagem" class="form-label">Imagem da Moto</label>
                        <input type="file" class="form-control" id="imagem" name="imagem" accept="image/*">
                    </div>
                </div>
                <div class="preview-container" id="preview-container"></div>
                <div class="text-center mt-4">
                    <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancelar</button>
                    <button type="reset" class="btn btn-secondary" id="clear-button">Limpar Campos</button>
                    <button type="submit" name="submit" class="btn btn-primary">Adicionar Moto</button>
                </div>
            </form>
        </div>
    </div>

    <div class="alert alert-success" id="successAlert">Moto registrada com sucesso!</div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/scripts/darkmode.js"></script>

    <script src="../assets/scripts/motos/motoadd.js"></script>

    <script>
        const success = <?php echo json_encode($success); ?>;
        if (success) {
            const successAlert = document.getElementById('successAlert');
            successAlert.style.display = 'block';
            setTimeout(() => {
                successAlert.style.opacity = '1';
            }, 100);
            setTimeout(() => {
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.style.display = 'none';
                }, 500);
            }, 3000);
        }
    </script>

</body>

</html>