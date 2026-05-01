<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tasks</title>

<link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">

<style>
/* Small additions for list layout */
.task-list {
    padding: 20px;
}

.task {
    background: #fafafa;
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 10px;
    border: 1px solid #eee;
}

.task.done {
    opacity: 0.6;
    text-decoration: line-through;
}

.task-actions {
    margin-top: 10px;
}

.task-actions button {
    margin-right: 5px;
    padding: 6px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.delete { background: #dc3545; color: white; }
.update { background: #28a745; color: white; }
.toggle { background: #ffc107; }
</style>
</head>

<body>

<div class="container">
    <div class="header">
        <h1>📋 Tasks</h1>
        <p>Your task list</p>
    </div>

    <div class="task-list" id="tasks">
        <p>Loading...</p>
    </div>

    <div class="view-tasks">
        <a href="<?= base_url('/') ?>">⬅ Back to Create</a>
    </div>
</div>

<script>
const API = "<?= base_url('api') ?>";

/* =========================
   LOAD TASKS (GET)
========================= */
  async function loadTasks() {
    const container = document.getElementById('tasks');

    try {
        const res = await fetch(`${API}/tasks`);
        const resData = await res.json();

        const tasks = resData.data; // 🔥 THIS is the actual array

        container.innerHTML = '';

        if (!tasks || !tasks.length) {
            container.innerHTML = "<p>No tasks yet</p>";
            return;
        }

        tasks.forEach(task => {
            const div = document.createElement('div');
            div.className = `task ${task.completed ? 'done' : ''}`;

            div.innerHTML = `
                <strong>${escape(task.title)}</strong><br>
                <small>${escape(task.description || '')}</small><br>
                <small>${task.created_at}</small>

                <div class="task-actions">
                    <button class="toggle" onclick="toggleTask(${task.id}, ${task.completed})">
                        ${task.completed ? 'Undo' : 'Complete'}
                    </button>

                    <button class="update" onclick="editTask(${task.id}, '${escape(task.title)}', '${escape(task.description || '')}')">
                        Edit
                    </button>

                    <button class="delete" onclick="deleteTask(${task.id})">
                        Delete
                    </button>
                </div>
            `;

            container.appendChild(div);
        });

    } catch (err) {
        console.error(err);
        container.innerHTML = "<p>Error loading tasks</p>";
    }
}

/* =========================
   DELETE (DELETE)
========================= */
async function deleteTask(id) {
    if (!confirm("Delete this task?")) return;

    await fetch(`${API}/tasks/${id}`, {
        method: 'DELETE'
    });

    loadTasks();
}

/* =========================
   TOGGLE COMPLETE (PATCH)
========================= */
async function toggleTask(id, current) {
    await fetch(`${API}/tasks/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            completed: current ? 0 : 1
        })
    });

    loadTasks();
}

/* =========================
   EDIT TASK (PATCH)
========================= */
async function editTask(id, title, desc) {
    const newTitle = prompt("Edit title:", title);
    if (!newTitle) return;

    const newDesc = prompt("Edit description:", desc);

    await fetch(`${API}/tasks/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            title: newTitle,
            description: newDesc
        })
    });

    loadTasks();
}

/* =========================
   SAFE TEXT
========================= */
function escape(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/* INIT */
loadTasks();
</script>

</body>
</html>