<?php
// Configurações básicas
define('DATA_FILE_PATH', __DIR__ . '/data/');

// Criar diretório de dados se não existir
if (!file_exists(DATA_FILE_PATH)) {
    mkdir(DATA_FILE_PATH, 0777, true);
}

// Arquivos de dados
define('TASKS_FILE', DATA_FILE_PATH . 'tasks.json');
define('EXPENSES_FILE', DATA_FILE_PATH . 'expenses.json');
define('SHOPPING_FILE', DATA_FILE_PATH . 'shopping.json');
define('MISSING_ITEMS_FILE', DATA_FILE_PATH . 'missing_items.json');
define('RESPONSIBLES_FILE', 'data/responsibles.json');


// Inicializar arquivos se não existirem
$initial_files = [
    TASKS_FILE => [],
    EXPENSES_FILE => [],
    SHOPPING_FILE => [],
    MISSING_ITEMS_FILE => []
];

foreach ($initial_files as $file => $default) {
    if (!file_exists($file)) {
        file_put_contents($file, json_encode($default));
    }
}
?>