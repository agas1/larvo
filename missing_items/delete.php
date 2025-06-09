<?php
require_once '../functions.php';

$id = $_GET['id'] ?? null;
if ($id !== null && is_numeric($id)) {
    $items = loadData(MISSING_ITEMS_FILE);
    
    if (isset($items[$id])) {
        unset($items[$id]);
        $items = array_values($items);
        saveData(MISSING_ITEMS_FILE, $items);
        
        $_SESSION['message'] = 'Item faltante excluído com sucesso!';
    }
}

header('Location: view.php');
exit;
?>