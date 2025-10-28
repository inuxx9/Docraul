<?php
// api/solicitar_cita.php

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Considera restringir esto en producción

// Incluir la configuración de la base de datos
require_once '../config/db.php';

// 1. Recibir y decodificar los datos JSON
$data = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($data)) {
    echo json_encode(['success' => false, 'message' => 'Método no permitido o datos vacíos.']);
    exit;
}

// 2. Extracción y Sanitización básica de datos
$nombre = trim($data['nombre'] ?? '');
$email = trim($data['email'] ?? '');
$telefono = trim($data['telefono'] ?? '');
$fecha_solicitud = trim($data['fecha_solicitud'] ?? '');
$hora_solicitud = trim($data['hora_solicitud'] ?? '');
$motivo = trim($data['motivo'] ?? '');

// 3. Validación
if (empty($nombre) || empty($email) || empty($telefono) || empty($fecha_solicitud) || empty($hora_solicitud)) {
    echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // 4. Buscar o Insertar Cliente
    $stmt = $pdo->prepare("SELECT cliente_id FROM clientes WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cliente) {
        // Cliente ya existe
        $cliente_id = $cliente['cliente_id'];
    } else {
        // Cliente no existe, insertarlo
        $sql = "INSERT INTO clientes (nombre, email, telefono) VALUES (:nombre, :email, :telefono)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'telefono' => $telefono
        ]);
        $cliente_id = $pdo->lastInsertId();
    }

    // 5. Insertar la Cita con estado 'Pendiente'
    $sql_cita = "INSERT INTO citas (cliente_id, fecha_solicitud, hora_solicitud, motivo, estado) 
                 VALUES (:cliente_id, :fecha, :hora, :motivo, 'Pendiente')";
    $stmt_cita = $pdo->prepare($sql_cita);
    $stmt_cita->execute([
        'cliente_id' => $cliente_id,
        'fecha' => $fecha_solicitud,
        'hora' => $hora_solicitud,
        'motivo' => $motivo
    ]);

    $pdo->commit();

    // 6. Respuesta de Éxito
    echo json_encode(['success' => true, 'message' => 'Cita registrada con éxito.']);

} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("Error de BD al solicitar cita: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error en el servidor al registrar la cita.']);
}
?>