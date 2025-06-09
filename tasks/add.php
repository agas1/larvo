<?php
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
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Tarefa</title>
    <style>
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
        
        .back-link {
            color: var(--purple-dark);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 20px;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Adicionar Nova Tarefa</h1>
        
        <form method="post">
            <div class="form-group">
                <label for="day">Dia da Semana:</label>
                <select name="day" id="day" required>
                    <option value="">Selecione um dia</option>
                    <option value="Segunda-feira">Segunda-feira</option>
                    <option value="Terça-feira">Terça-feira</option>
                    <option value="Quarta-feira">Quarta-feira</option>
                    <option value="Quinta-feira">Quinta-feira</option>
                    <option value="Sexta-feira">Sexta-feira</option>
                    <option value="Sábado">Sábado</option>
                    <option value="Domingo">Domingo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="room">Cômodo:</label>
                <select name="room" id="room" required>
                    <option value="">Selecione um cômodo</option>
                    <option value="Banheiro">Banheiro</option>
                    <option value="Cozinha">Cozinha</option>
                    <option value="Sala">Sala</option>
                    <option value="Quarto">Quarto</option>
                    <option value="Varanda">Varanda</option>
                    <option value="Área de Serviço">Área de Serviço</option>
                    <option value="Garagem">Garagem</option>
                </select>
            </div>
            
            <div class="form-group">
                <label for="task">Tarefa:</label>
                <input type="text" name="task" id="task" required placeholder="Ex.: Limpar o chão, Organizar armário...">
            </div>
            
            <div class="form-group">
                <label for="responsible">Responsável:</label>
                <select name="responsible" id="responsible" required>
                    <option value="">Selecione o responsável</option>
                    <option value="Agatha">Agatha</option>
                    <option value="Amanda">Amanda</option>
                    <option value="Ambos">Ambos</option>
                </select>
            </div>
            
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="done" id="done">
                    <label for="done" style="margin-bottom: 0; color: var(--gray-dark);">Tarefa já concluída?</label>
                </div>
            </div>
            
            <div class="form-actions">
                <a href="view.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn">Adicionar Tarefa</button>
            </div>
        </form>
    </div>
</body>
</html>