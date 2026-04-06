<?php
date_default_timezone_set('America/Bogota'); 
include('../../Inicio-de-sesion/db.php');
include('../Core/VehiculoDAO.php');

$vehDAO = new VehiculoDAO($conexion);
$busqueda = isset($_GET['search']) ? strtoupper(mysqli_real_escape_string($conexion, $_GET['search'])) : '';
$listaVehiculos = $vehDAO->listarConFiltro($busqueda);

$alertaJS = "";
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'exists') $alertaJS = "Swal.fire('¡Atención!', 'Este vehículo ya está en sitio', 'warning');";
    if ($_GET['status'] == 'success') $alertaJS = "Swal.fire('¡Éxito!', 'Operación realizada correctamente', 'success');";
    if ($_GET['status'] == 'deleted') $alertaJS = "Swal.fire('Eliminado', 'El registro ha sido borrado', 'success');";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehículos | RuedaSport</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    
    <style>
        /* Ajuste de contenedor para centrar el contenido */
        .container {
            display: grid;
            width: 96%;
            margin: 0 auto;
            gap: 1.8rem;
            grid-template-columns: 14rem auto; 
        }

        main {
            margin-top: 1.4rem;
            max-width: 1000px; 
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        .form-container, .recent-orders {
            background: var(--color-white);
            padding: var(--card-padding);
            border-radius: var(--card-border-radius);
            box-shadow: var(--box-shadow);
            margin-bottom: 2rem;
            transition: all 300ms ease;
        }

        .form-crear {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1.5rem;
            background: #f0f2f5; 
            padding: 20px;
            border-radius: var(--border-radius-2);
        }

        .form-crear input, .form-crear select {
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #dce1e7;
            outline: none;
            width: 100%;
        }

        .btn-registrar {
            background: #6c7ae0; 
            color: white;
            border: none;
            padding: 12px;
            cursor: pointer;
            font-weight: 600;
            border-radius: 8px;
        }

        /* Badges de estado */
        .badge {
            padding: 5px 10px; border-radius: 15px; font-size: 0.7rem; font-weight: 700;
        }
        .badge-sitio { background: #e6fcf5; color: #0ca678; }
        .badge-retirado { background: #fff5f5; color: #fa5252; }

        /* CORRECCIÓN: Estilos para evitar que las acciones se salgan del marco */
        #tablaVehiculos {
            width: 100%;
            border-collapse: collapse;
        }

        #tablaVehiculos th, #tablaVehiculos td {
            padding: 12px 15px;
        }

        /* Alineamos la última columna (Acciones) a la derecha con margen interno */
        #tablaVehiculos th:last-child, 
        #tablaVehiculos td:last-child {
            text-align: right;
            padding-right: 25px; 
        }

        .action-group {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            align-items: center;
        }
    </style>
</head>
<body>

<div class="container">
    <aside>
        <div class="top"><div class="logo"><h2>RUEDA<span>SPORT</span></h2></div></div>
        <div class="sidebar">
            <a href="index.php"><i class='bx bxs-grid-alt'></i><h3>Inicio</h3></a>
            <a href="empleados.php"><i class='bx bx-user'></i><h3>Empleados</h3></a>
            <a href="vehiculos.php" class="active"><i class='bx bx-car'></i><h3>Vehículos</h3></a>
            <a href="../../index.html" class="logout"><i class='bx bx-log-out'></i><h3>Cerrar sesión</h3></a>
        </div>
    </aside>

    <main>
        <h1 class="animate__animated animate__fadeInDown">Gestión de Vehículos</h1>

        <div class="form-container animate__animated animate__fadeInLeft">
            <h2 style="margin-bottom: 1.2rem;">Registrar Nuevo Ingreso</h2>
            <form action="registrar_vehiculo.php" method="POST" class="form-crear">
                <div>
                    <label style="font-size: 0.8rem; display: block; margin-bottom: 5px;">Placa</label>
                    <input type="text" name="placa" placeholder="Ej: ABC123" required style="text-transform: uppercase;">
                </div>
                <div>
                    <label style="font-size: 0.8rem; display: block; margin-bottom: 5px;">Tipo de Vehículo</label>
                    <select name="tipo">
                        <option value="Carro">Carro</option>
                        <option value="Moto">Moto</option>
                    </select>
                </div>
                <div>
                    <label style="font-size: 0.8rem; display: block; margin-bottom: 5px;">Valor Base</label>
                    <input type="number" name="valor" value="5000">
                </div>
                <div style="display: flex; align-items: flex-end;">
                    <button type="submit" class="btn-registrar" style="width: 100%;">Registrar Vehículo</button>
                </div>
            </form>
        </div>

        <div class="recent-orders animate__animated animate__fadeInUp">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2>Listado de Vehículos</h2>
                <div style="position: relative;">
                    <i class='bx bx-search' style="position: absolute; left: 10px; top: 10px; color: var(--color-info-dark);"></i>
                    <input type="text" id="busquedaRealTime" onkeyup="buscarTabla()" placeholder="Buscar placa o estado..." 
                           style="padding: 8px 8px 8px 35px; border-radius: 8px; border: 1px solid #dce1e7; width: 250px;">
                </div>
            </div>

            <table id="tablaVehiculos">
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Estado</th>
                        <th>Ingreso / Salida</th>
                        <th style="text-align: right;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($v = mysqli_fetch_assoc($listaVehiculos)): 
                        $salida = $v['fecha_salida'];
                        $enSitio = (empty($salida) || $salida == '0000-00-00 00:00:00');
                    ?>
                    <tr>
                        <td><b><?php echo strtoupper($v['placa']); ?></b></td>
                        <td>
                            <span class="badge <?php echo $enSitio ? 'badge-sitio' : 'badge-retirado'; ?>">
                                <?php echo $enSitio ? 'EN SITIO' : 'RETIRADO'; ?>
                            </span>
                        </td>
                        <td style="font-size: 0.8rem; color: var(--color-info-dark);">
                            IN: <?php echo date('H:i', strtotime($v['fecha_ingreso'])); ?> | 
                            OUT: <?php echo !$enSitio ? date('H:i', strtotime($salida)) : '--:--'; ?>
                        </td>
                        <td>
                            <div class="action-group">
                                <?php if($enSitio): ?>
                                    <button onclick="confirmarSalida(<?php echo $v['id']; ?>, '<?php echo $v['placa']; ?>')" 
                                            style="background: #ffbb55; color: white; border: none; padding: 5px 12px; border-radius: 15px; cursor: pointer; font-size: 0.7rem;">Salida</button>
                                <?php endif; ?>
                                <i class='bx bx-edit-alt' onclick="editar(<?php echo $v['id']; ?>, '<?php echo $v['placa']; ?>')" style="color: #6c7ae0; cursor: pointer; font-size: 1.2rem;"></i>
                                <i class='bx bx-trash' onclick="eliminar(<?php echo $v['id']; ?>, '<?php echo $v['placa']; ?>')" style="color: #ff7782; cursor: pointer; font-size: 1.2rem;"></i>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

<script>
    <?php echo $alertaJS; ?>

    function buscarTabla() {
        let input = document.getElementById("busquedaRealTime").value.toUpperCase();
        let rows = document.getElementById("tablaVehiculos").getElementsByTagName("tr");
        for (let i = 1; i < rows.length; i++) {
            rows[i].style.display = rows[i].innerText.toUpperCase().includes(input) ? "" : "none";
        }
    }

    function confirmarSalida(id, placa) {
        Swal.fire({
            title: '¿Confirmar Salida?',
            text: "Vehículo: " + placa,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffbb55',
            confirmButtonText: 'Sí, marcar salida'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = "dar_salida.php?id=" + id;
        })
    }

    function eliminar(id, placa) {
        Swal.fire({
            title: '¿Eliminar registro?',
            text: "Placa: " + placa,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ff7782'
        }).then((r) => {
            if(r.isConfirmed) window.location.href = 'eliminar_vehiculo.php?id=' + id;
        });
    }

    async function editar(id, placa) {
        const { value: nPlaca } = await Swal.fire({
            title: 'Editar Placa',
            input: 'text',
            inputValue: placa,
            showCancelButton: true
        });
        if(nPlaca) window.location.href = `editar_vehiculo.php?id=${id}&placa=${nPlaca.toUpperCase()}`;
    }
</script>
</body>
</html>