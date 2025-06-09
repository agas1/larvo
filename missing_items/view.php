<?php
require_once '../functions.php';

$missingItems = loadData(MISSING_ITEMS_FILE);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens Faltantes</title>
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
            max-width: 1200px;
            margin: 0 auto;
            background-color: var(--white);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        h1 {
            color: var(--purple-dark);
            margin-bottom: 20px;
            text-align: center;
        }
        
        .header-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }
        
        .btn {
            background-color: var(--purple-pastel);
            color: var(--white);
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }
        
        .btn:hover {
            background-color: var(--purple-dark);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--gray-light);
        }
        
        th {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
        }
        
        tr:hover {
            background-color: var(--gray-white);
        }
        
        .priority-high {
            background-color: #fee2e2;
            color: #991b1b;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .priority-medium {
            background-color: #fef9c3;
            color: #854d0e;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .priority-low {
            background-color: #dcfce7;
            color: #166534;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }
        
        .notes {
            color: var(--gray-dark);
            font-size: 0.9rem;
        }
        
        .actions a {
            color: var(--purple-dark);
            margin-right: 10px;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .actions a:hover {
            color: var(--purple-pastel);
            text-decoration: underline;
        }
        
        .back-link {
            display: inline-block;
            margin-top: 20px;
            color: var(--purple-dark);
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Itens Faltantes</h1>
        
        <div class="header-actions">
            <a href="add.php" class="btn">+ Novo Item</a>
            <a href="../index.php" class="btn">Voltar ao Dashboard</a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Prioridade</th>
                    <th>Observações</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($missingItems as $id => $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td>
                        <span class="priority-<?= strtolower($item['priority']) ?>">
                            <?= htmlspecialchars($item['priority']) ?>
                        </span>
                    </td>
                    <td class="notes"><?= htmlspecialchars($item['notes']) ?></td>
                    <td class="actions">
                        <a href="update.php?id=<?= $id ?>">Editar</a>
                        <a href="delete.php?id=<?= $id ?>" onclick="return confirm('Tem certeza?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if (empty($missingItems)): ?>
        <p style="text-align: center; margin-top: 20px; color: var(--gray-dark);">
            Nenhum item faltante registrado.
        </p>
        <?php endif; ?>
    </div>
</body>
</html>