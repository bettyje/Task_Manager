<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Task</title>

    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
</head>

<body>

<div class="container">
    <div class="header">
        <h1>📝 Create New Task</h1>
        <p>Add a new task to your list</p>
    </div>

    <div class="form-container">
        <div id="alert" class="alert"></div>

        <div class="form-group">
            <label>Title <span class="required">*</span></label>
            <input type="text" id="title" placeholder="Enter task title...">
        </div>

        <div class="form-group">
            <label>Description (optional)</label>
            <input type="text" id="description" placeholder="Enter task description...">
        </div>

        <div class="form-group">
            <label>
                <input type="checkbox" id="completed">
                Mark as completed
            </label>
        </div>

        <button class="btn btn-primary" onclick="createTask()">
            ✨ Create Task
        </button>

        <div class="view-tasks">
            <a href="<?= base_url('tasks') ?>">📋 View All Tasks</a>
        </div>
    </div>
</div>

<script>
const BASE_URL = "<?= base_url('api') ?>";

async function createTask() {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const completed = document.getElementById('completed').checked ? 1 : 0;

    if (!title) {
        showAlert('Title is required!', 'error');
        return;
    }

    const btn = document.querySelector('.btn-primary');
    btn.disabled = true;
    btn.textContent = 'Creating...';

    try {
        const response = await fetch(`${BASE_URL}/tasks`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                title,
                description,
                completed
            })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to create task');
        }

        showAlert('Task created successfully!', 'success');

        setTimeout(() => {
            window.location.href = "<?= base_url('tasks') ?>";
        }, 800);

    } catch (error) {
        showAlert(error.message, 'error');
    } finally {
        btn.disabled = false;
        btn.textContent = '✨ Create Task';
    }
}

function showAlert(message, type) {
    const alert = document.getElementById('alert');
    alert.textContent = message;
    alert.className = `alert alert-${type} show`;

    setTimeout(() => {
        alert.classList.remove('show');
    }, 3000);
}

document.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        createTask();
    }
});
</script>

</body>
</html>