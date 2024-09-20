<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "SELECT * FROM motos_dados WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $moto = $result->fetch_assoc();
    } else {
        echo "Nenhuma moto encontrada.";
        exit;
    }
} else {
    echo "ID não especificado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage Club | Detalhes da Moto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <link rel="shortcut icon" href="../assets/img/gestao.png" type="image/x-icon">

    <link rel="stylesheet" href="../assets/css/motos/motos.css">

    <link rel="stylesheet" href="../assets/css/motos/motoview.css">

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
                            <li><a class="dropdown-item" href="customers/add.php"> <i class="fa-solid fa-plus"></i> Novo
                                    item </a></li>
                            <li><a class="dropdown-item" href="customers"> <i class="fa-solid fa-motorcycle"></i> Ver
                                    Motos</a></li>
                            <li class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#" id="nestedDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">Mais Opções</a>
                                <ul class="dropdown-menu" aria-labelledby="nestedDropdown">
                                    <li><a class="dropdown-item" href="#">Sub-item 1</a></li>
                                    <li><a class="dropdown-item" href="#">Sub-item 2</a></li>
                                </ul>
                            </li>
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
        <h1>Detalhes da Moto</h1>

        <div class="text-center">
            <?php if ($moto['imagem']) { ?>
                <img src="data:image/jpeg;base64,<?php echo base64_encode($moto['imagem']); ?>" class="moto-image"
                    alt="Imagem da Moto">
            <?php } else { ?>
                <p>Sem imagem disponível</p>
            <?php } ?>
        </div>

        <div class="moto-details">
            <table>
                <tr>
                    <th>Marca</th>
                    <td><?php echo htmlspecialchars($moto['marca']); ?></td>
                </tr>
                <tr>
                    <th>Modelo</th>
                    <td><?php echo htmlspecialchars($moto['modelo']); ?></td>
                </tr>
                <tr>
                    <th>Motor</th>
                    <td><?php echo htmlspecialchars($moto['motor']); ?> CC</td>
                </tr>
                <tr>
                    <th>Ano</th>
                    <td><?php echo htmlspecialchars($moto['ano']); ?></td>
                </tr>
                <tr>
                    <th>Cor</th>
                    <td><?php echo htmlspecialchars($moto['cor']); ?></td>
                </tr>
                <tr>
                    <th>Quilometragem</th>
                    <td><?php echo number_format($moto['quilometragem'], 0, ',', '.'); ?> km</td>
                </tr>
            </table>
        </div>

        <div class="text-center">
            <a href="index.php" class="btn-back">Voltar</a>
        </div>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/scripts/darkmode.js"></script>

</body>

</html>