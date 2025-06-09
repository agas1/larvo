<?php
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'] ?? '';
    $id = $_POST['id'] ?? null;
    
    switch ($type) {
        case 'task':
            $task = [
                'task' => $_POST['task'],
                'room' => $_POST['room'],
                'day' => $_POST['day'],
                'responsible' => $_POST['responsible'],
                'done' => isset($_POST['done']) ? true : false,
                'created_at' => $id ? loadData(TASKS_FILE)[$id]['created_at'] : date('Y-m-d H:i:s')
            ];
            
            if ($id !== null) {
                updateTask($id, $task);
            } else {
                addTask($task);
            }
            break;
            
        case 'expense':
            $expense = [
                'description' => $_POST['description'],
                'amount' => (float)$_POST['amount'],
                'due_date' => (int)$_POST['due_date'],
                'paid' => isset($_POST['paid']) ? true : false,
                'created_at' => $id ? loadData(EXPENSES_FILE)[$id]['created_at'] : date('Y-m-d H:i:s')
            ];
            
            if ($id !== null) {
                updateExpense($id, $expense);
            } else {
                addExpense($expense);
            }
            break;
            
        case 'shopping':
            $item = [
                'name' => $_POST['name'],
                'category' => $_POST['category'],
                'price' => (float)$_POST['price'],
                'needed' => isset($_POST['needed']) ? true : false,
                'created_at' => $id ? loadData(SHOPPING_FILE)[$id]['created_at'] : date('Y-m-d H:i:s')
            ];
            
            if ($id !== null) {
                updateShoppingItem($id, $item);
            } else {
                addShoppingItem($item);
            }
            break;
            
        case 'missing':
            $item = [
                'name' => $_POST['name'],
                'priority' => $_POST['priority'],
                'notes' => $_POST['notes'],
                'created_at' => $id ? loadData(MISSING_ITEMS_FILE)[$id]['created_at'] : date('Y-m-d H:i:s')
            ];
            
            if ($id !== null) {
                updateMissingItem($id, $item);
            } else {
                addMissingItem($item);
            }
            break;
    }
    
    header('Location: dashboard.php');
    exit;
}
?>