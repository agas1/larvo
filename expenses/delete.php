<?php
require_once '../functions.php';

$id = $_GET['id'] ?? null;
if ($id !== null && is_numeric($id)) {
    $expenses = loadData(EXPENSES_FILE);
    
    if (isset($expenses[$id])) {
        unset($expenses[$id]);
        $expenses = array_values($expenses);
        saveData(EXPENSES_FILE, $expenses);
        
        $_SESSION['message'] = 'Gasto fixo excluído com sucesso!';
    }
}

header('Location: view.php');
exit;
?>  