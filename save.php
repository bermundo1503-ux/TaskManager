<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
require_once 'conn.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = isset($_POST['title']) ? $_POST['title'] : null;
        $description = isset($_POST['description']) ? $_POST['description'] : null;

        if (!empty($title)) {
            $sql = "INSERT INTO crud_php (title, description) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Erro ao preparar a consulta: " . $conn->error);
            }

            $stmt->bind_param("ss", $title, $description);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Tarefa salva com sucesso!";
                $_SESSION['message_type'] = "success";
            } else {
                $_SESSION['message'] = "Erro ao salvar a tarefa!";
                $_SESSION['message_type'] = "danger";
            }

            $stmt->close();
            header("Location: index.php");
            exit();
        } else {
            throw new Exception("O campo 'Título' é obrigatório!");
        }
    } else {
        throw new Exception("Método de requisição inválido!");
    }
} catch (Exception $e) {
    $_SESSION['message'] = "Erro: " . $e->getMessage();
    $_SESSION['message_type'] = "danger";
    header("Location: index.php");
    exit();
} finally {
    $conn->close();
}