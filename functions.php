<?php
require_once 'config.php';

function loadData($file) {
    if (!file_exists($file)) {
        return [];
    }
    $data = file_get_contents($file);
    return json_decode($data, true) ?: [];
}

function saveData($file, $data) {
    $result = file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));
    return $result !== false;
}

// Funções para Tarefas
function addTask($task) {
    $tasks = loadData(TASKS_FILE);
    $tasks[] = $task;
    return saveData(TASKS_FILE, $tasks);
}

function updateTask($id, $updatedTask) {
    $tasks = loadData(TASKS_FILE);
    if (isset($tasks[$id])) {
        $tasks[$id] = $updatedTask;
        return saveData(TASKS_FILE, $tasks);
    }
    return false;
}

// Funções para Gastos Fixos
function addExpense($expense) {
    $expenses = loadData(EXPENSES_FILE);
    $expenses[] = $expense;
    return saveData(EXPENSES_FILE, $expenses);
}

function updateExpense($id, $updatedExpense) {
    $expenses = loadData(EXPENSES_FILE);
    if (isset($expenses[$id])) {
        $expenses[$id] = $updatedExpense;
        return saveData(EXPENSES_FILE, $expenses);
    }
    return false;
}

// Funções para Compras
function addShoppingItem($item) {
    $shopping = loadData(SHOPPING_FILE);
    $shopping[] = $item;
    return saveData(SHOPPING_FILE, $shopping);
}

function updateShoppingItem($id, $updatedItem) {
    $shopping = loadData(SHOPPING_FILE);
    if (isset($shopping[$id])) {
        $shopping[$id] = $updatedItem;
        return saveData(SHOPPING_FILE, $shopping);
    }
    return false;
}

// Funções para Itens Faltantes
function addMissingItem($item) {
    $missingItems = loadData(MISSING_ITEMS_FILE);
    $missingItems[] = $item;
    return saveData(MISSING_ITEMS_FILE, $missingItems);
}

function updateMissingItem($id, $updatedItem) {
    $missingItems = loadData(MISSING_ITEMS_FILE);
    if (isset($missingItems[$id])) {
        $missingItems[$id] = $updatedItem;
        return saveData(MISSING_ITEMS_FILE, $missingItems);
    }
    return false;
}
?>