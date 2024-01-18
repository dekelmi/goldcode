function showGlobalTaskForm() {
    const taskForm = document.getElementById('taskForm');
    const addTaskBtnGlobal = document.getElementById('addTaskBtnGlobal');

    taskForm.style.display = 'block';
    addTaskBtnGlobal.style.display = 'none';

    const taskNameInput = taskForm.querySelector('input[name="taskName"]');
    taskNameInput.focus();
}

function addTask() {
    const taskName = document.getElementById('taskName').value;
    const taskDate = document.getElementById('taskDate').value;
    showGlobalButton();

    // Check ввода даты

    const checkDate = /^\d{2}\.\d{2}\.\d{4}$/;
    if (!checkDate.test(taskDate)) {
        alert('Неверный формат даты. Пожалуйста, используйте формат ДД.ММ.ГГГГ');
        return;
    }

    if (taskName && taskDate) {
        const taskListDiv = document.getElementById('taskList');

        const taskBlock = document.createElement('div');
        taskBlock.classList.add('taskBlock');

        const nameDiv = document.createElement('div');
        nameDiv.textContent = 'Название: ' + taskName;

        const dateAddedDiv = document.createElement('div');
        dateAddedDiv.textContent = 'Дата добавления: ' + getCurrentDate();

        const dateEndDiv = document.createElement('div');
        dateEndDiv.textContent = 'Дата окончания: ' + taskDate;

        taskBlock.appendChild(nameDiv);
        taskBlock.appendChild(dateAddedDiv);
        taskBlock.appendChild(dateEndDiv);

        taskListDiv.insertBefore(taskBlock, taskListDiv.firstChild);

        document.getElementById('taskName').value = '';
        document.getElementById('taskDate').value = '';

        document.getElementById('taskForm').style.display = 'none';
        document.getElementById('addTaskBtn').style.marginBottom = '0';
    } else {
        alert('Пожалуйста, заполните все поля.');
    }
}

function getCurrentDate() {
    const currentDate = new Date();
    const day = String(currentDate.getDate()).padStart(2, '0');
    const month = String(currentDate.getMonth() + 1).padStart(2, '0');
    const year = currentDate.getFullYear();
    return day + '.' + month + '.' + year;
}