<?php
require_once 'functions.php';

$tasks = loadData(TASKS_FILE);
$expenses = loadData(EXPENSES_FILE);
$shopping = loadData(SHOPPING_FILE);
$missingItems = loadData(MISSING_ITEMS_FILE);

$pendingTasks = array_filter($tasks, fn($t) => !$t['done']);
$unpaidExpenses = array_filter($expenses, fn($e) => !$e['paid']);
$neededItems = array_filter($shopping, fn($i) => $i['needed']);

session_start();
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: ../login.php');
    exit;
}

function getLaundryScheduleAla400() {
    $dayOfWeek = date('N'); // 1 (segunda) a 7 (domingo)
    
    // Mapeamento completo dos horários conforme a tabela
    $fullSchedule = [
        1 => ['06:00-12:00', '12:00-17:00', '17:00-22:00'], // Segunda
        2 => ['06:00-12:00', '12:00-17:00', '17:00-22:00'], // Terça
        3 => ['06:00-12:00', '12:00-17:00', '17:00-22:00'], // Quarta
        4 => ['06:00-12:00', '12:00-17:00', '17:00-22:00'], // Quinta
        5 => ['06:00-12:00', '12:00-17:00', '17:00-22:00'], // Sexta
        6 => ['06:00-12:00', '12:00-17:00', '17:00-22:00'], // Sábado
        7 => ['06:00-12:00', '12:00-17:00', '17:00-22:00']  // Domingo
    ];
    
    // Filtra apenas os horários da Ala 400
    $ala400Schedule = [
        1 => ['06:00-12:00'],    // Segunda
        2 => ['17:00-22:00'],    // Terça
        3 => ['12:00-17:00'],    // Quarta
        4 => ['06:00-12:00'],    // Quinta
        5 => ['17:00-22:00'],    // Sexta
        6 => ['12:00-17:00'],    // Sábado
        7 => ['12:00-17:00']     // Domingo
    ];
    
    // Mapeamento dos nomes dos dias
    $daysMap = [
        1 => 'Segunda-feira',
        2 => 'Terça-feira',
        3 => 'Quarta-feira', 
        4 => 'Quinta-feira',
        5 => 'Sexta-feira',
        6 => 'Sábado',
        7 => 'Domingo'
    ];
    
    // Função auxiliar para calcular o próximo dia de forma circular
    function getNextDay($currentDay, $offset) {
        $nextDay = $currentDay + $offset;
        return $nextDay > 7 ? $nextDay - 7 : $nextDay;
    }
    
    return [
        'full_schedule' => $fullSchedule,
        'ala400_schedule' => $ala400Schedule,
        'days_map' => $daysMap,
        'today' => [
            'day_num' => $dayOfWeek,
            'day_name' => $daysMap[$dayOfWeek] ?? 'Dia desconhecido',
            'schedule' => $ala400Schedule[$dayOfWeek] ?? []
        ],
        'next_days' => [
            [
                'day_num' => getNextDay($dayOfWeek, 1),
                'day_name' => $daysMap[getNextDay($dayOfWeek, 1)] ?? 'Dia desconhecido',
                'schedule' => $ala400Schedule[getNextDay($dayOfWeek, 1)] ?? []
            ],
            [
                'day_num' => getNextDay($dayOfWeek, 2),
                'day_name' => $daysMap[getNextDay($dayOfWeek, 2)] ?? 'Dia desconhecido',
                'schedule' => $ala400Schedule[getNextDay($dayOfWeek, 2)] ?? []
            ]
        ]
    ];
}

$laundryInfo = getLaundryScheduleAla400();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Larvo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --purple-pastel: #d8b4fe;
            --purple-light: #e9d5ff;
            --purple-dark: #8a63d2;
            --purple-darker: #7b4edd;
            --gray-white: #f8fafc;
            --gray-light: #e2e8f0;
            --gray-medium: #94a3b8;
            --gray-dark: #475569;
            --white: #ffffff;
            --success: #dcfce7;
            --success-dark: #16a34a;
            --warning: #fee2e2;
            --warning-dark: #dc2626;
            --info: #dbeafe;
            --info-dark: #2563eb;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: var(--gray-white);
            color: var(--gray-dark);
            line-height: 1.6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        /* Header Styles */
        .app-header {
            background: linear-gradient(135deg, var(--purple-dark), var(--purple-darker));
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
            width: 100%;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header-logo {
            height: 50px;
            width: auto;
            max-width: 150px;
            object-fit: contain;
        }
        
        .header-title {
            color: white;
            font-size: 1.8rem;
            font-weight: 600;
            margin-left: 1rem;
            flex-grow: 1;
            text-align: center;
        }
        
        .header-right {
            display: flex;
            align-items: center;
        }
        
        /* Main Content Styles */
        .main-container {
            flex: 1;
            width: 100%;
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 1.5rem;
        }
        
        /* Navigation Styles */
        .app-nav {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            width: 100%;
        }
        
        .nav-list {
            display: flex;
            justify-content: center;
            list-style: none;
            flex-wrap: wrap;
            gap: 0.75rem;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: var(--purple-dark);
            font-weight: 500;
            padding: 0.75rem 1.25rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            background-color: var(--gray-white);
        }
        
        .nav-link:hover {
            background-color: var(--purple-pastel);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        /* Dashboard Styles */
        .dashboard-title {
            text-align: center;
            margin-bottom: 1.5rem;
            color: var(--purple-dark);
            font-size: 1.8rem;
            font-weight: 600;
            position: relative;
            padding-bottom: 0.5rem;
        }
        
        .dashboard-title::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background: linear-gradient(to right, var(--purple-pastel), var(--purple-dark));
            border-radius: 3px;
        }
        
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        /* Card Styles */
        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            border-left: 4px solid var(--purple-pastel);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .card-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--purple-light);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1rem;
            color: var(--purple-dark);
            font-size: 1.2rem;
        }
        
        .card h3 {
            color: var(--purple-dark);
            font-size: 1.3rem;
            font-weight: 600;
        }
        
        .card-content {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .count {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--purple-dark);
            margin: 0.5rem 0;
            line-height: 1;
        }
        
        .count-detail {
            font-size: 1rem;
            color: var(--gray-medium);
            margin-bottom: 1rem;
        }
        
        .progress-container {
            margin-top: 1rem;
        }
        
        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            color: var(--gray-dark);
        }
        
        .progress {
            height: 8px;
            background-color: var(--gray-light);
            border-radius: 4px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            border-radius: 4px;
            background: linear-gradient(to right, var(--purple-pastel), var(--purple-dark));
            transition: width 0.6s ease;
        }
        
        /* Lavanderia Styles */
        .laundry-section {
            background-color: var(--white);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }
        
        .laundry-title {
            color: var(--purple-dark);
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 2px solid var(--purple-light);
            padding-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .laundry-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1.5rem;
        }
        
        .laundry-table th, 
        .laundry-table td {
            padding: 0.75rem;
            text-align: center;
            border: 1px solid var(--gray-light);
        }
        
        .laundry-table th {
            background-color: var(--purple-light);
            color: var(--purple-dark);
            font-weight: 600;
        }
        
        .laundry-table tr:nth-child(even) {
            background-color: var(--gray-white);
        }
        
        .today-highlight {
            background-color: var(--purple-pastel) !important;
            font-weight: 600;
        }
        
        .time-slot {
            display: inline-block;
            padding: 0.5rem 1rem;
            margin: 0.25rem;
            background-color: var(--purple-light);
            color: var(--purple-dark);
            border-radius: 20px;
            font-size: 0.9rem;
        }
        
        .ala400-slot {
            background-color: var(--success);
            color: var(--success-dark);
            font-weight: 600;
        }
        
        .next-days-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1.5rem;
        }
        
        .next-day-card {
            background-color: var(--white);
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--purple-pastel);
        }
        
        .next-day-title {
            color: var(--purple-dark);
            font-weight: 600;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        /* Footer Styles */
        footer {
            text-align: center;
            padding: 1.5rem;
            color: var(--gray-medium);
            font-size: 0.9rem;
            border-top: 1px solid var(--gray-light);
            background-color: var(--white);
            width: 100%;
            margin-top: auto;
        }
        
        .logout-link {
            color: white;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s;
        }
        
        .logout-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive Styles */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                padding: 1rem;
                gap: 1rem;
            }
            
            .header-logo {
                height: 40px;
                max-width: 120px;
            }
            
            .header-title {
                font-size: 1.5rem;
                margin-left: 0;
                order: 1;
            }
            
            .header-right {
                order: 2;
            }
            
            .nav-list {
                flex-direction: column;
                align-items: center;
                gap: 0.5rem;
            }
            
            .nav-link {
                width: 100%;
                justify-content: center;
            }
            
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
            
            .count {
                font-size: 2rem;
            }
            
            .laundry-table {
                display: block;
                overflow-x: auto;
            }
            
            .next-days-container {
                grid-template-columns: 1fr;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .card {
            animation: fadeIn 0.5s ease forwards;
        }
        
        .card:nth-child(1) { animation-delay: 0.1s; }
        .card:nth-child(2) { animation-delay: 0.2s; }
        .card:nth-child(3) { animation-delay: 0.3s; }
        .card:nth-child(4) { animation-delay: 0.4s; }
        .card:nth-child(5) { animation-delay: 0.5s; }
    </style>
</head>
<body>
  <header class="app-header">
    <div class="header-container">
       <img src="img/logolarvo.png" alt="Logo Larvo" class="header-logo">
       <div class="header-right">
           <a href="logout.php" class="logout-link">
               <i class="fas fa-sign-out-alt"></i> Sair
           </a>
       </div>
    </div>
  </header>
  
  <div class="main-container">
    <nav class="app-nav">
      <ul class="nav-list">
        <li>
          <a href="tasks/view.php" class="nav-link">
            <i class="fas fa-broom"></i>
            <span>Tarefas</span>
          </a>
        </li>
        <li>
          <a href="expenses/view.php" class="nav-link">
            <i class="fas fa-file-invoice-dollar"></i>
            <span>Gastos</span>
          </a>
        </li>
        <li>
          <a href="shopping/view.php" class="nav-link">
            <i class="fas fa-shopping-cart"></i>
            <span>Compras</span>
          </a>
        </li>
        <li>
          <a href="missing_items/view.php" class="nav-link">
            <i class="fas fa-box-open"></i>
            <span>Itens Faltantes</span>
          </a>
        </li>
      </ul>
    </nav>
    
    <h2 class="dashboard-title">Resumo Geral</h2>
    
    <div class="dashboard-grid">
      <div class="card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fas fa-broom"></i>
          </div>
          <h3>Tarefas</h3>
        </div>
        <div class="card-content">
          <div class="count"><?= count($pendingTasks) ?>/<?= count($tasks) ?></div>
          <p class="count-detail">Tarefas pendentes</p>
          <div class="progress-container">
            <div class="progress-label">
              <span>Progresso</span>
              <span><?= count($tasks) ? round((count($pendingTasks)/count($tasks))*100) : 0 ?>%</span>
            </div>
            <div class="progress">
              <div class="progress-bar" style="width: <?= count($tasks) ? (count($pendingTasks)/count($tasks)*100) : 0 ?>%"></div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fas fa-file-invoice-dollar"></i>
          </div>
          <h3>Gastos</h3>
        </div>
        <div class="card-content">
          <div class="count"><?= count($unpaidExpenses) ?></div>
          <p class="count-detail">Contas a pagar</p>
          <div class="progress-container">
            <div class="progress-label">
              <span>Progresso</span>
              <span><?= count($expenses) ? round((count($unpaidExpenses)/count($expenses))*100) : 0 ?>%</span>
            </div>
            <div class="progress">
              <div class="progress-bar" style="width: <?= count($expenses) ? (count($unpaidExpenses)/count($expenses)*100) : 0 ?>%"></div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <h3>Compras</h3>
        </div>
        <div class="card-content">
          <div class="count"><?= count($neededItems) ?></div>
          <p class="count-detail">Itens necessários</p>
          <div class="progress-container">
            <div class="progress-label">
              <span>Progresso</span>
              <span><?= count($shopping) ? round((count($neededItems)/count($shopping))*100) : 0 ?>%</span>
            </div>
            <div class="progress">
              <div class="progress-bar" style="width: <?= count($shopping) ? (count($neededItems)/count($shopping)*100) : 0 ?>%"></div>
            </div>
          </div>
        </div>
      </div>
    
      <div class="card">
        <div class="card-header">
          <div class="card-icon">
            <i class="fas fa-box-open"></i>
          </div>
          <h3>Itens Faltantes</h3>
        </div>
        <div class="card-content">
          <div class="count"><?= count($missingItems) ?></div>
          <p class="count-detail">Itens para repor</p>
          <div class="progress-container">
            <div class="progress-label">
              <span>Status</span>
              <span>100%</span>
            </div>
            <div class="progress">
              <div class="progress-bar" style="width: 100%"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- Seção da Lavanderia no index principal -->
    <div class="laundry-section">
        <h2 class="laundry-title">
            <i class="fas fa-tshirt"></i> Lavanderia - Ala 400
        </h2>
        
        <div style="margin-bottom: 1.5rem;">
            <h3 style="color: var(--purple-dark); margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                <i class="fas fa-calendar-day"></i> Hoje - <?= $laundryInfo['today']['day_name'] ?>
            </h3>
            <?php if (!empty($laundryInfo['today']['schedule'])): ?>
                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <?php foreach ($laundryInfo['today']['schedule'] as $timeSlot): ?>
                        <span class="time-slot ala400-slot">
                            <i class="fas fa-clock"></i> <?= $timeSlot ?>
                        </span>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Não há horários disponíveis para a Ala 400 hoje.</p>
            <?php endif; ?>
        </div>
        
        <table class="laundry-table">
            <thead>
                <tr>
                    <th>Dia da Semana</th>
                    <th>06:00 às 12:00</th>
                    <th>12:00 às 17:00</th>
                    <th>17:00 às 22:00</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($laundryInfo['full_schedule'] as $dayNum => $timeSlots): ?>
                    <tr <?= $dayNum == $laundryInfo['today']['day_num'] ? 'class="today-highlight"' : '' ?>>
                        <td><?= $laundryInfo['days_map'][$dayNum] ?></td>
                        <td>
                            <?php if (in_array('06:00-12:00', $laundryInfo['ala400_schedule'][$dayNum] ?? [])): ?>
                                <span class="ala400-slot">Ala 400</span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (in_array('12:00-17:00', $laundryInfo['ala400_schedule'][$dayNum] ?? [])): ?>
                                <span class="ala400-slot">Ala 400</span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (in_array('17:00-22:00', $laundryInfo['ala400_schedule'][$dayNum] ?? [])): ?>
                                <span class="ala400-slot">Ala 400</span>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3 style="color: var(--purple-dark); margin: 1.5rem 0 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i class="fas fa-arrow-right"></i> Próximos Dias - Ala 400
        </h3>
        <div class="next-days-container">
            <?php foreach ($laundryInfo['next_days'] as $day): ?>
                <div class="next-day-card">
                    <h4 class="next-day-title">
                        <i class="fas fa-calendar-day"></i> <?= $day['day_name'] ?>
                    </h4>
                    <?php if (!empty($day['schedule'])): ?>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <?php foreach ($day['schedule'] as $timeSlot): ?>
                                <span class="time-slot ala400-slot">
                                    <i class="fas fa-clock"></i> <?= $timeSlot ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p>Não há horários agendados para este dia.</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
  </div>
  
  <footer>
    &copy; <?= date('Y') ?> Larvo - Todos os direitos reservados.
  </footer>
</body>
</html>