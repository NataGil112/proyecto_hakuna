<?php

include 'funciones.php';
include 'db_connection.php';

$config = include 'PHP/config.php';
$dsn = 'mysql:host=' . $config['db']['host'] . ';dbname=' . $config['db']['name'];
    try {
        $conexion = new PDO($dsn, $config['db']['user'], $config['db']['pass'], $config['db']['options']);
    } catch (PDOException $error) {
        echo 'Error en la base de datos: ' . $error->getMessage();
        exit();
    }

if (isset($_POST['submit'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $sql = "SELECT * FROM Cliente WHERE ID_Cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $usuario_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    $resultado = [
        'error' => false,
        'mensaje' => 'La cita para ' . htmlspecialchars($usuario['nombre']) . ' ha sido agregada con éxito'
    ];

    try {
        // Insertar nueva mascota
        $nombre_mascota = $_POST['nombre_mascota'];
        $raza = $_POST['raza'];
        $sexo = $_POST['sexo'];
        $tamaño = $_POST['tamaño'];

        $consultaMascotaSQL = "INSERT INTO Mascota (nombre, raza, sexo, tamaño, ID_Cliente) VALUES (:nombre, :raza, :sexo, :tamano, :id_cliente)";
        $stmtMascota = $conexion->prepare($consultaMascotaSQL);
        $stmtMascota->execute([
            ':nombre' => $nombre_mascota,
            ':raza' => $raza,
            ':sexo' => $sexo,
            ':tamano' => $tamaño,
            ':id_cliente' => $usuario_id
        ]);

        $ID_Mascota = $conexion->lastInsertId();

        // Insertar cita
        $fecha = $_POST['fecha'];
        $hora = $_POST['hora'];
        $ID_Servicio = $_POST['servicio'];

        $consultaCitaSQL = "INSERT INTO citas (ID_Cliente, ID_Mascota, fecha, hora, ID_Servicio) VALUES (:id_cliente, :id_mascota, :fecha, :hora, :id_servicio)";
        $stmtCita = $conexion->prepare($consultaCitaSQL);
        $stmtCita->execute([
            ':id_cliente' => $usuario_id,
            ':id_mascota' => $ID_Mascota,
            ':fecha' => $fecha,
            ':hora' => $hora,
            ':id_servicio' => $ID_Servicio
        ]);
    } catch (PDOException $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'Error en la base de datos: ' . $error->getMessage();
    } catch (Exception $error) {
        $resultado['error'] = true;
        $resultado['mensaje'] = 'Error general: ' . $error->getMessage();
    }
}

?>

<?php
if (isset($resultado)) {
?>
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-<?= $resultado['error'] ? 'danger' : 'success' ?>" role="alert">
                    <?= htmlspecialchars($resultado['mensaje']) ?>
                </div>
            </div>
        </div>
    </div>
<?php
}
?>
<div class="container" id="formucita">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-4">Agendar una cita</h2>
            <hr>
            <form method="post">
                <div class="form-group">
                    <label for="nombre_mascota">Nombre mascota</label>
                    <input type="text" name="nombre_mascota" id="nombre_mascota" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="raza">Raza</label>
                    <input type="text" name="raza" id="raza" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="tamaño">Tamaño</label>
                    <input type="text" name="tamaño" id="tamaño" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <input type="text" name="sexo" id="sexo" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="servicio">Servicio</label>
                    <select name="servicio" id="servicio" class="form-control" required>
                        <?php
                        // Obtener los servicios preestablecidos desde la base de datos
                        $sqlServicios = "SELECT * FROM servicios";
                        foreach ($conexion->query($sqlServicios) as $servicio) {
                            echo "<option value='" . $servicio['ID_Servicios'] . "'>" . htmlspecialchars($servicio['nombre']) . " - $" . htmlspecialchars($servicio['precio']) . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <br>
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <label for="hora">Hora</label>
                    <input type="time" name="hora" id="hora" class="form-control" required>
                </div>
                <br>
                <div class="form-group">
                    <input type="submit" name="submit" class="btn btn-primary" value="Enviar"> 
                </div>
                <br>
            </form>
        </div>
    </div>
</div>
