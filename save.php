<?php
require_once 'conn.php';

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $title = isset($_POST['title']) ? ($_POST['title']) : null;
        $description= isset($_POST['description'])  ? $_POST['description']: null;
 
        $sql = "INSERT INTO crud_php (title, description) VALUES (?,?)";
        if (!empty($title)) {
            $sql = "INSERT INTO crud_php (title, description) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Erro ao preparar a consulta: " . $conn->error);
            }

            $stmt->bind_param("ss", $title, $description);

            if ($stmt->execute()) {
                // Redireciona de volta para index.php após salvar
                header("Location: index.php");
                exit();
            } else {
                throw new Exception("Erro ao executar a consulta: " . $stmt->error);
            }

            $stmt->close();
        } else {
            throw new Exception("O campo 'Título' é obrigatório!");
        }
    } else {
        throw new Exception("Método de requisição inválido!");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
} finally {
    $conn->close();
}
