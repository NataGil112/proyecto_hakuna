<?php
session_start();

include 'db_connection.php';

$resultado = '';
$error = false;

if (isset($_POST['submit'])) {
    // Obtener los datos del formulario
    $email = $_POST['correo'];
    $password = $_POST['password'];

    // Evitar inyección SQL
    $email = $conn->real_escape_string($email);

    // Consulta para verificar las credenciales del usuario
    $sql = "SELECT * FROM cliente WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // El usuario existe, verificar la contraseña
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['contraseña'])) {
            // Contraseña correcta, iniciar sesión
            $_SESSION['usuario_id'] = $row['ID_Cliente'];
            $_SESSION['nombre'] = $row['nombre'];
            header("Location: perfil.php"); // Redirigir a la página de perfil
            exit;
        } else {
            // Contraseña incorrecta
            $resultado = "Usuario o contraseña incorrecta.";
            $error = true;
        }
    } else {
        // El usuario no existe
        $resultado = "Usuario o contraseña incorrecta.";
        $error = true;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>HAKUNA MATATA PETS</title>
	<link rel="stylesheet" href="CSS/style_ingreso.css">
    <link rel="stylesheet" href="CSS/style_base.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">	
</head>
	
<body style="background-color: rgb(255, 255, 255)">

	<header id="masthead" class="site-header header-main-wrapper">
		<h1>HAKUNA MATATA PETS</h1>
		<nav>
			<ul class="nav-links">
				<li><a href="index.html"><strong>INICIO</strong></a></li>
				<li><a href="quienesomos.html"><strong>QUIÉNES SOMOS</strong></a></li>
				<li><a href="servicios.php"><strong>SERVICIOS</strong></a></li>
				<li><a href="registro.php"><strong>REGISTRO</strong></a></li>
			  <li><a href="ingreso.php"><strong>INGRESO</strong></a></li>
				<li><a href="perfil.php"><strong>PERFIL</strong></a></li>
			</ul>
			<button class="menu-toggle">Menú</button>
		  </nav>
		</header>
<!-- De acá hacia arriba NO TOCAR -->

<?php if (!empty($resultado)) { ?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-<?= $error ? 'danger' : 'success' ?>" role="alert">
                    <?= $resultado ?>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

  <div class="container" id="formuingreso"> <!--div container -->
    <div class="row" style= "justify-content: center;"> 
      <div class="col-4">

        <h2>INGRESO</h2>

        <form method="post">
        	<input type="text" name="correo" id="correo" placeholder="Digita tu correo electronico" class="form-control"> <br>
          	<input type="password" name="password" id="password" placeholder="Digita tu contraseña" class="form-control"> <br>

          <input type="submit" name="submit" class="btn btn-danger">
        </form>
      </div>
	  <div class="col-md-6">
		<img src="IMAGENES/mascotas.jpg" class="peluqueria" alt="">
	</div>
    </div>
  </div> <!--div container -->
<br>


<!-- De acá hacia abajo NO TOCAR -->
    <footer>
		<h4> Informacion de contacto</h4>
		<ul>
			<li>Telefono: 1234567</li>
			<li> Correo: hakunamatatapets@gmail.com</li>
			<li> Direccion:  Calle  10 76 18</li>
		</ul>
		<p> @Copyright 2050 de nadie. Todos los derechos revertidos</p>

	</footer>

    </body>
    </html>