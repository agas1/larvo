<?php
require_once 'config.php';
require_once 'functions.php';

require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task = [
        'day' => $_POST['day'],
        'room' => $_POST['room'],
        'task' => $_POST['task'],
        'responsible' => $_POST['responsible'],
        'done' => isset($_POST['done']) ? true : false,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    addTask($task);
    header('Location: view.php');
    exit;
}


session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $taskText = filter_input(INPUT_POST, 'task', FILTER_SANITIZE_STRING);
        
        if (empty($taskText)) {
            throw new Exception("Texto da tarefa vazio");
        }

        $newTask = [
            'id' => uniqid(),
            'text' => $taskText,
            'created_at' => date('Y-m-d H:i:s'),
            'completed' => false
        ];

        if (addTask($newTask)) {
            $_SESSION['message'] = "Tarefa adicionada com sucesso!";
        } else {
            throw new Exception("Falha ao salvar tarefa");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

header('Location: index.php');
exit;?>