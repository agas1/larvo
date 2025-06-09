<?php
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expense = [
        'description' => $_POST['description'],
        'amount' => (float)$_POST['amount'],
        'due_date' => (int)$_POST['due_date'],
        'paid' => isset($_POST['paid']) ? true : false,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    addExpense($expense);
    header('Location: view.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Gasto Fixo</title>
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
        input[type="number"] {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid var(--gray-light);
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus,
        input[type="number"]:focus {
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
        <h1>Adicionar Gasto Fixo</h1>
        
        <form method="post">
            <div class="form-group">
                <label for="description">Descrição:</label>
                <input type="text" name="description" id="description" required placeholder="Ex.: Aluguel, Internet...">
            </div>

            <div class="form-group">
                <label for="amount">Valor:</label>
                <input type="number" step="0.01" name="amount" id="amount" required placeholder="Ex.: 500.00">
            </div>

            <div class="form-group">
                <label for="due_date">Dia de Vencimento:</label>
                <input type="number" min="1" max="31" name="due_date" id="due_date" required placeholder="Ex.: 5">
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="paid" id="paid">
                    <label for="paid" style="margin-bottom: 0; color: var(--gray-dark);">Já está pago?</label>
                </div>
            </div>

            <div class="form-actions">
                <a href="view.php" class="btn btn-secondary">Cancelar</a>
                <button type="submit" class="btn">Adicionar Gasto</button>
            </div>
        </form>
    </div>
</body>
</html>
