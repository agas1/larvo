<?php
require_once '../functions.php';

$id = $_GET['id'] ?? null;
if ($id !== null && is_numeric($id)) {
    $tasks = loadData(TASKS_FILE);
    
    if (isset($tasks[$id])) {
        unset($tasks[$id]);
        $tasks = array_values($tasks);
        saveData(TASKS_FILE, $tasks);
        
        $_SESSION['message'] = 'Tarefa excluída com sucesso!';
    }
}

header('Location: view.php');
exit;
?>