<?php
require_once '../functions.php';

$id = $_GET['id'] ?? null;
if ($id !== null && is_numeric($id)) {
    $items = loadData(SHOPPING_FILE);
    
    // Verifica se o ID existe
    if (isset($items[$id])) {
        // Remove o item
        unset($items[$id]);
        // Reorganiza os índices do array
        $items = array_values($items);
        saveData(SHOPPING_FILE, $items);
        
        // Mensagem de sucesso (opcional)
        $_SESSION['message'] = 'Item excluído com sucesso!';
    }
}

// Redireciona de volta para a lista
header('Location: view.php');
exit;
?>