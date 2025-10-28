<?php
// dentista/agregar_dentista.php

// Incluimos el chequeo de acceso y la conexión a la BD.
// RUTA CORREGIDA: Sube un nivel (a la raíz) y entra a 'recepcionista/'
include '../recepcionista/auth_check.php'; 

// NOTA: Si auth_check.php está correcto, la variable $pdo ahora existe aquí.

$mensaje = '';
$error = '';

// --- 1. Lógica de Inserción (Manejo del POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $especialidad = trim($_POST['especialidad'] ?? '');

    if (empty($nombre) || empty($especialidad)) {
        $error = 'El nombre y la especialidad del dentista son obligatorios.';
    } else {
        try {
            // Prepara y ejecuta la inserción del nuevo dentista
            $sql = "INSERT INTO dentistas (nombre, especialidad) VALUES (:nombre, :especialidad)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nombre' => $nombre, ':especialidad' => $especialidad]);
            
            $mensaje = '✅ ¡Dentista ' . htmlspecialchars($nombre) . ' agregado con éxito!';
        } catch (PDOException $e) {
            $error = '❌ Error al agregar el dentista. Revise que la tabla "dentistas" exista. Detalle: ' . $e->getMessage();
            error_log($error);
        }
    }
}

// --- 2. Lógica de Listado ---
try {
    // Consulta para obtener la lista actual de dentistas
    $sql_listado = "SELECT dentista_id, nombre, especialidad FROM dentistas ORDER BY nombre ASC";
    $dentistas_actuales = $pdo->query($sql_listado)->fetchAll();
} catch (PDOException $e) {
    // Si falla la consulta (ej. tabla no existe)
    $error .= ' Error al listar dentistas: No se pudo acceder a la tabla.';
    $dentistas_actuales = [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Dentistas</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-green-700 mb-6">⚙️ Administración de Dentistas</h1>
        <p class="mb-4 text-gray-600">
            <a href="../recepcionista/panel_citas.php" class="text-blue-500 hover:underline">Volver al Panel de Citas</a>
        </p>

        <?php if ($mensaje): ?>
            <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded-lg"><?= $mensaje ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg"><?= $error ?></div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-green-600 border-b pb-2">Agregar Nuevo Dentista</h2>
            <form method="POST">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                        <input type="text" id="nombre" name="nombre" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500">
                    </div>
                    <div>
                        <label for="especialidad" class="block text-sm font-medium text-gray-700">Especialidad</label>
                        <input type="text" id="especialidad" name="especialidad" required
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                               placeholder="Ej: Ortodoncia, Cirugía Maxilofacial">
                    </div>
                </div>
                <div class="mt-6 text-right">
                    <button type="submit"
                            class="bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-150 font-semibold">
                        Guardar Dentista
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-4 text-green-600 border-b pb-2">Dentistas Registrados (<?= count($dentistas_actuales) ?>)</h2>
            
            <?php if (empty($dentistas_actuales) && !$error): ?>
                <p class="text-gray-500">Aún no hay dentistas registrados.</p>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Especialidad</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($dentistas_actuales as $d): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= $d['dentista_id'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($d['nombre']) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?= htmlspecialchars($d['especialidad']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>