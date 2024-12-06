<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Garage Club | Dashboard | CRUD-PW2</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="assets/img/gestao.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/modal.css">

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <span class="brand-garage">Garage</span>
                <span class="brand-club">Club</span>
            </a>
            <div class="d-flex">
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

    <div class="start">
        <center>
            <h3 class="text-init">Faça Login ou Registro para poder ter acesso<br>as ferramentas do dashboard</h3>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 fade-in">
                <a href="user/login/login.php">
                    <div class="btn btn-primary btn-icon text-white custom-btn"
                        style="background-color: #BA181B; border: 0;">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        <span>Fazer Login</span>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4 fade-in">
                <a href="user/register/register.php">
                    <div class="btn btn-primary btn-icon text-white custom-btn"
                        style="background-color: #660708; border: 0;">
                        <i class="fa-solid fa-user-plus"></i>
                        <span>Fazer Registro</span>
                    </div>
                </a>
            </div>
        </center>
    </div>

    <footer class="footer text-center fade-in">
        <p class="mb-0">&copy; 2024 Garage Club | Leonardo e Pedro</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/scripts/darkmode.js"></script>

</body>

</html>