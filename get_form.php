<?php
require_once 'functions.php';

$type = $_GET['type'] ?? '';
$id = $_GET['id'] ?? null;

// Função para gerar options de select
function generateOptions($options, $selected = '') {
    $html = '';
    foreach ($options as $value => $label) {
        $selectedAttr = $value == $selected ? 'selected' : '';
        $html .= "<option value=\"$value\" $selectedAttr>$label</option>";
    }
    return $html;
}

switch ($type) {
    case 'task':
        $task = $id !== null ? loadData(TASKS_FILE)[$id] : null;
        ?>
        <div class="form-group">
            <label for="task">Descrição da Tarefa:</label>
            <input type="text" id="task" name="task" required value="<?= $task['task'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="room">Cômodo:</label>
            <select id="room" name="room" required>
                <?= generateOptions([
                    'Banheiro' => 'Banheiro',
                    'Cozinha' => 'Cozinha',
                    'Sala' => 'Sala',
                    'Quarto' => 'Quarto',
                    'Varanda' => 'Varanda'
                ], $task['room'] ?? '') ?>
            </select>
        </div>
        <div class="form-group">
            <label for="day">Dia da Semana:</label>
            <select id="day" name="day" required>
                <?= generateOptions([
                    'Segunda' => 'Segunda-feira',
                    'Terça' => 'Terça-feira',
                    'Quarta' => 'Quarta-feira',
                    'Quinta' => 'Quinta-feira',
                    'Sexta' => 'Sexta-feira',
                    'Sábado' => 'Sábado',
                    'Domingo' => 'Domingo'
                ], $task['day'] ?? '') ?>
            </select>
        </div>
        <div class="form-group">
            <label for="responsible">Responsável:</label>
            <select id="responsible" name="responsible" required>
                <?= generateOptions([
                    'Agatha' => 'Agatha',
                    'Amanda' => 'Amanda',
                    'Ambos' => 'Ambos'
                ], $task['responsible'] ?? '') ?>
            </select>
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="done" <?= isset($task['done']) && $task['done'] ? 'checked' : '' ?>>
                Tarefa concluída
            </label>
        </div>
        <?php
        break;

    case 'expense':
        $expense = $id !== null ? loadData(EXPENSES_FILE)[$id] : null;
        ?>
        <div class="form-group">
            <label for="description">Descrição:</label>
            <input type="text" id="description" name="description" required value="<?= $expense['description'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="amount">Valor (R$):</label>
            <input type="number" step="0.01" id="amount" name="amount" required value="<?= $expense['amount'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="due_date">Dia de Vencimento:</label>
            <input type="number" min="1" max="31" id="due_date" name="due_date" required value="<?= $expense['due_date'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="paid" <?= isset($expense['paid']) && $expense['paid'] ? 'checked' : '' ?>>
                Gasto pago
            </label>
        </div>
        <?php
        break;

    case 'shopping':
        $item = $id !== null ? loadData(SHOPPING_FILE)[$id] : null;
        ?>
        <div class="form-group">
            <label for="name">Nome do Item:</label>
            <input type="text" id="name" name="name" required value="<?= $item['name'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="category">Categoria:</label>
            <select id="category" name="category" required>
                <?= generateOptions([
                    'Mercado' => 'Mercado',
                    'Casa' => 'Casa',
                    'Outros' => 'Outros'
                ], $item['category'] ?? '') ?>
            </select>
        </div>
        <div class="form-group">
            <label for="price">Preço (R$):</label>
            <input type="number" step="0.01" id="price" name="price" required value="<?= $item['price'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>
                <input type="checkbox" name="needed" <?= isset($item['needed']) && $item['needed'] ? 'checked' : '' ?>>
                Item necessário
            </label>
        </div>
        <?php
        break;

    case 'missing':
        $item = $id !== null ? loadData(MISSING_ITEMS_FILE)[$id] : null;
        ?>
        <div class="form-group">
            <label for="name">Nome do Item:</label>
            <input type="text" id="name" name="name" required value="<?= $item['name'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label for="priority">Prioridade:</label>
            <select id="priority" name="priority" required>
                <?= generateOptions([
                    'Alta' => 'Alta',
                    'Média' => 'Média',
                    'Baixa' => 'Baixa'
                ], $item['priority'] ?? '') ?>
            </select>
        </div>
        <div class="form-group">
            <label for="notes">Observações:</label>
            <textarea id="notes" name="notes"><?= $item['notes'] ?? '' ?></textarea>
        </div>
        <?php
        break;

    default:
        echo '<p>Formulário não encontrado</p>';
}
?>