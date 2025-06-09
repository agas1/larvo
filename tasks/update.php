<?php
require_once '../functions.php';

$id = $_GET['id'] ?? null;
$tasks = loadData(TASKS_FILE);

if ($id === null || !isset($tasks[$id])) {
    header('Location: view.php');
    exit;
}

$task = $tasks[$id];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $updatedTask = [
        'day' => $_POST['day'],
        'room' => $_POST['room'],
        'task' => $_POST['task'],
        'responsible' => $_POST['responsible'],
        'done' => isset($_POST['done']) ? true : false,
        'created_at' => $task['created_at']
    ];
    
    if (updateTask($id, $updatedTask)) {
        header('Location: view.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Tarefa</title>
    <style>
        /* Mesmo estilo da página add.php */
        :root {
            --purple-pastel: #d8b4fe;
            --purple-light: #e9d5ff;
            --purple-dark: #7e22ce;
            --gray-white: #f3f4f6;
            --gray-light: #e5e7eb;
            --gray-dark: #6b7280;
            --white: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--gray-white);
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1 {
            color: var(--purple-dark);
            margin-bottom: 30px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--purple-dark);
        }
        
        input[type="text"],
        input[type="number"],
        select,
        textarea {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--gray-light);
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        textarea:focus {
            border-color: var(--purple-pastel);
            outline: none;
            box-shadow: 0 0 0 3px var(--purple-light);
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .checkbox-group input[type="checkbox"] {
            width: auto;
        }
        
        .btn {
            background-color: var(--purple-pastel);
            color: var(--white);
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 500;
            transition: background-color 0.3s;
            display: inline-block;
            text-decoration: none;
            margin-top: 10px;
        }
        
        .btn:hover {
            background-color: var(--purple-dark);
        }
        
        .btn-secondary {
            background-color: var(--gray-light);
            color: var(--gray-dark);
        }
        
        .btn-secondary:hover {
            background-color: var(--gray-dark);
            color: var(--white);
        }
        
        .form-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Editar Tarefa</h1>
        
        <form method="post">
            <div class="form-group">
                <label for="day">Dia da Semana:</label>
                <select name="day" id="day" required>
                    <option value="Segunda-feira" <?= $task['day'] === 'Segunda-feira' ? 'selected' : '' ?>>Segunda-feira</option>
                    <option value="Terça-feira" <?= $task['day'] === 'Terça-feira' ? 'selected' : '' ?>>Terça-feira</option>
                    <option value="Quarta-feira" <?= $task['day'] === 'Quarta-feira' ? 'selected' : '' ?>>Quarta-feira</option>
                    <option value="Quinta-feira" <?= $task['day'] === 'Quinta-feira' ? 'selected' : '' ?>>Quinta-feira</option>
                    <option value="Sexta-feira" <?= $task['day'] === 'Sexta-feira' ? 'selected' : '' ?>>Sexta-feira</option>
                    <option value="Sábado" <?= $task['day'] === 'Sábado' ? 'selected' : '' ?>>Sábado</option>
                    <option value="Domingo" <?= $task['day'] === 'Domingo' ? 'selected' : '' ?>>Domingo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="room">Cômodo:</label>
                <select name="room" id="room" required>
                    <option value="Banheiro" <?= $task['room'] === 'Banheiro' ? 'selected' : '' ?>>Banheiro</option>
                    <option value="Cozinha" <?= $task['room'] === 'Cozinha' ? 'selected' : '' ?>>Cozinha</option>
                    <option value="Sala" <?= $task['room'] === 'Sala' ? 'selected' : '' ?>>Sala</option>
                    <option value="Quarto" <?= $task['room'] === 'Quarto' ? 'selected' : '' ?>>Quarto</option>
                    <option value="Varanda" <?= $task['room'] === 'Varanda' ? 'selected' : '' ?>>Varanda</option>
                    <option value="Área de Serviço" <?= $task['room'] === 'Área de Serviço' ? 'selected' : '' ?>>Área de Serviço</option>
                    <option value="Garagem" <?= $task['room'] === 'Garagem' ? 'selected' : '' ?>>Garagem</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="task">Tarefa:</label>
                <input type="text" name="task" id="task" required value="<?= htmlspecialchars($task['task']) ?>">
            </div>
            
            <div class="form-group">
                <label for="responsible">Responsável:</label>
                <select name="responsible" id="responsible" required>
                    <option value="Agatha" <?= $task['responsible'] === 'Agatha' ? 'selected' : '' ?>>Agatha</option>
                    <option value="Amanda" <?= $task['responsible'] === 'Amanda' ? 'selected' : '' ?>>Amanda</option>
                    <option value="Ambos" <?= $task['responsible'] === 'Ambos' ? 'selected' : '' ?>>Ambos</option>
                </select>
            </div>
            
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="done" id="done" <?= $task['done'] ? 'checked' : '' ?>>
                    <label for="done" style="margin-bottom: 0; color: var(--gray-dark);">Tarefa concluída</label>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="view.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn">Atualizar Tarefa</button>
            </div>
        </form>
    </div>
</body>
</html>