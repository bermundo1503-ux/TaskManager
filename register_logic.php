<?php

require_once 'conn.php';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) ? $_POST['email'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $confirm_password = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : null;

        if ($email && $password && $confirm_password) {
            if ($password !== $confirm_password) {
                throw new Exception("Senha não coincide");
            }
            if (!$conn || $conn->connect_error) {
                throw new Exception("Conexão com o banco de dados não está ativa");
            }

            // Usa PASSWORD_DEFAULT para máxima compatibilidade
            $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("ss", $email, $hashedpassword);
                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit();
                } else {
                    throw new Exception("Erro ao executar o cadastro: " . $stmt->error);
                }
                $stmt->close();
            } else {
                throw new Exception("Erro ao preparar a consulta: " . $conn->error);
            }
        } else {
            throw new Exception("Email, senha e confirmação de senha são obrigatórios!");
        }
    } else {
        throw new Exception("Método de requisição inválido!");
    }
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
} finally {
    if (isset($conn) && !$conn->connect_error) {
        $conn->close();
    }
}