<?php
// recepcionista/panel_citas.php

// Inclusión del chequeo de acceso y la conexión a la BD
include 'auth_check.php'; 

// 1. Obtener Citas Pendientes
$sql_citas = "SELECT c.cita_id, cl.nombre, cl.telefono, cl.email, c.fecha_solicitud, c.hora_solicitud, c.motivo 
              FROM citas c JOIN clientes cl ON c.cliente_id = cl.cliente_id 
              WHERE c.estado = 'Pendiente' ORDER BY c.fecha_creacion ASC";
$stmt_citas = $pdo->query($sql_citas);
$citasPendientes = $stmt_citas->fetchAll();

// 2. Obtener la lista de Dentistas
$sql_dentistas = "SELECT dentista_id, nombre FROM dentistas ORDER BY nombre ASC";
$stmt_dentistas = $pdo->query($sql_dentistas);
$dentistas = $stmt_dentistas->fetchAll();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Citas - Recepcionista</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold text-indigo-700 mb-8">Gestión de Citas Pendientes</h1>
        
        <?php if (isset($_GET['mensaje'])): ?>
            <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"><?= htmlspecialchars($_GET['mensaje']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">Error: <?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <?php if (empty($citasPendientes)): ?>
            <div class="p-6 bg-white rounded-lg shadow-md text-center text-gray-500">
                🎉 No hay citas pendientes por asignar.
            </div>
        <?php else: ?>
            <div class="bg-white shadow-xl rounded-lg overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora Sol.</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Motivo</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 uppercase">Asignar Dentista</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($citasPendientes as $cita): ?>
                            <tr class="hover:bg-yellow-50">
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900"><?= htmlspecialchars($cita['nombre']) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-600">Tel: <?= htmlspecialchars($cita['telefono']) ?><br>Email: <?= htmlspecialchars($cita['email']) ?></td>
                                <td class="px-4 py-3 text-sm text-indigo-600"><?= htmlspecialchars($cita['fecha_solicitud']) ?> @ <?= htmlspecialchars($cita['hora_solicitud']) ?></td>
                                <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate" title="<?= htmlspecialchars($cita['motivo']) ?>"><?= htmlspecialchars(substr($cita['motivo'], 0, 40)) ?>...</td>
                                
                                <td class="px-4 py-3 whitespace-nowrap text-center text-sm font-medium">
                                    <form method="POST" action="asignar_dentista.php" class="flex items-center space-x-2">
                                        <input type="hidden" name="cita_id" value="<?= $cita['cita_id'] ?>">
                                        
                                        <select name="dentista_id" required class="border border-gray-300 rounded-md py-1 px-2 text-sm focus:ring-blue-500">
                                            <option value="">Selecciona Dentista</option>
                                            <?php foreach ($dentistas as $dentista): ?>
                                                <option value="<?= $dentista['dentista_id'] ?>"><?= htmlspecialchars($dentista['nombre']) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        
                                        <button type="submit" 
                                                class="bg-blue-600 text-white py-1 px-3 rounded-md hover:bg-blue-700 transition duration-150 text-xs font-semibold">
                                            Confirmar Cita
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>