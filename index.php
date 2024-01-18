<?php
ob_start();
session_start();

// Чтения данных из XML файла
function readFromXml() {
    $xmlPath = 'tasks.xml';

    if (!file_exists($xmlPath) || filesize($xmlPath) == 0) {
        $emptyXml = new SimpleXMLElement('<tasks></tasks>');
        $emptyXml->asXML($xmlPath);
    }

    $xml = simplexml_load_file($xmlPath);

    $taskList = array();
    if ($xml && count($xml->task) > 0) {
        foreach ($xml->task as $task) {
            $taskList[] = array(
                'taskName' => (string)$task->name,
                'taskDate' => (string)$task->date
            );
        }
    }

    return $taskList;
}

if (empty($_SESSION['taskList'])) {
    $_SESSION['taskList'] = readFromXml();
}


// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $taskName = $_POST['taskName'];
    $taskDate = $_POST['taskDate'];

    if ($taskName && $taskDate) {
        $newTask = array('taskName' => $taskName, 'taskDate' => $taskDate);
        array_unshift($_SESSION['taskList'], $newTask);

        saveToXml($_SESSION['taskList']);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Сохранения данных в XML файл
function saveToXml($data) {
    $xml = new SimpleXMLElement('<tasks></tasks>');

    foreach ($data as $task) {
        $taskElem = $xml->addChild('task');
        $taskElem->addChild('name', $task['taskName']);
        $taskElem->addChild('date', $task['taskDate']);
    }

    $xml->asXML('tasks.xml');
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Список задач</title>
</head>
<body>
    <div id="app">
        <button id="addTaskBtnGlobal" onclick="showGlobalTaskForm()">Добавить</button>
        <div id="taskForm" style="display: none;">
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="text" name="taskName" placeholder="Название задачи">
        <input type="date" name="taskDate">
        <button type="submit">Добавить</button>
    </form>
</div>
        <div id="taskList">
        <?php
            $taskList = array_reverse($_SESSION['taskList']);
                foreach ($taskList as $task) {
                echo '<div class="taskBlock">';
                echo '<div>Название: ' . $task['taskName'] . '</div>';
                echo '<div>Дата добавления: ' . date('d.m.Y') . '</div>';
                echo '<div>Дата окончания: ' . $task['taskDate'] . '</div>';
                echo '</div>';
            }
        ?>
        </div>
    </div>
    <script src="app.js"></script>
</body>
</html>