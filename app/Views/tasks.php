<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks</title>
    <link rel="stylesheet" href="<?= base_url('css/styles.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/tasks.css') ?>">
    
</head>
<body>

<div class="container">
    <div class="header">
        <h1>📋 Tasks</h1>
        <p>Your task list</p>
    </div>
    <div class="view-tasks top-link">
        <a href="<?= base_url('/') ?>">➕ Create New Task</a>
    </div>

    

    <div class="table-wrapper">
        <div id="loading" class="loading">Loading tasks...</div>
        
        <div id="tasksContent" style="display: none;">
            <table class="task-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tasksBody">
                </tbody>
            </table>
        </div>
    </div>

    
</div>

<script>
const API = "<?= base_url('api') ?>";

async function loadTasks() {
    try {
        const res = await fetch(`${API}/tasks`);
        const resData = await res.json();

        const tasks = resData.data || resData;

        const loadingDiv = document.getElementById('loading');
        const contentDiv = document.getElementById('tasksContent');
        const tbody = document.getElementById('tasksBody');

        loadingDiv.style.display = 'none';
        contentDiv.style.display = 'block';
        tbody.innerHTML = '';

        if (!tasks || !tasks.length) {
            tbody.innerHTML = '<tr><td colspan="5" class="empty">No tasks yet.</td></tr>';
            return;
        }

        tasks.forEach(task => {
            const row = tbody.insertRow();

            const isCompleted = Number(task.completed) === 1;

            if (isCompleted) {
                row.classList.add('task-done'); 
            }

            const titleCell = row.insertCell(0);
            titleCell.innerHTML = `<strong class="${isCompleted ? 'text-done' : ''}">
                ${escape(task.title)}
            </strong>`;

            const descCell = row.insertCell(1);
            descCell.innerHTML = `<span class="${isCompleted ? 'text-done' : ''}">
                ${escape(task.description || '—')}
            </span>`;

            const statusCell = row.insertCell(2);
            const statusSpan = document.createElement('span');
            statusSpan.className = `status-badge ${
                isCompleted ? 'status-completed' : 'status-pending'
            }`;
            statusSpan.textContent = isCompleted ? '✓ Completed' : '○ Pending';
            statusCell.appendChild(statusSpan);

            const dateCell = row.insertCell(3);
            dateCell.textContent = new Date(task.created_at).toLocaleString();
            dateCell.classList.add('task-date');

            const actionsCell = row.insertCell(4);
            const actionsDiv = document.createElement('div');
            actionsDiv.className = 'action-buttons';

            const toggleBtn = document.createElement('button');
            toggleBtn.className = 'btn-toggle';
            toggleBtn.textContent = isCompleted ? 'Mark Incomplete' : 'Mark Complete';
            toggleBtn.onclick = () => toggleTask(task.id, task.completed);

            const editBtn = document.createElement('button');
            editBtn.className = 'btn-edit';
            editBtn.textContent = '✏ Edit';
            editBtn.onclick = () => editTask(task.id, task.title, task.description);

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'btn-delete';
            deleteBtn.textContent = '🗑 Delete';
            deleteBtn.onclick = () => deleteTask(task.id);

            actionsDiv.appendChild(toggleBtn);
            actionsDiv.appendChild(editBtn);
            actionsDiv.appendChild(deleteBtn);
            actionsCell.appendChild(actionsDiv);
        });

    } catch (err) {
        console.error(err);
        document.getElementById('loading').innerHTML =
            '<div class="empty">Error loading tasks</div>';
    }
}

async function toggleTask(id, current) {
    try {
        const newValue = Number(current) === 1 ? 0 : 1;

        const res = await fetch(`${API}/tasks/${id}`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                completed: newValue
            })
        });

        if (!res.ok) throw new Error('Update failed');

        loadTasks();

    } catch (err) {
        console.error(err);
        alert('Toggle failed');
    }
}

async function deleteTask(id) {
    if (!confirm("Delete this task?")) return;

    await fetch(`${API}/tasks/${id}`, {
        method: 'DELETE'
    });

    loadTasks();
}

async function editTask(id, title, desc) {
    const newTitle = prompt("Edit title:", title);
    if (!newTitle || newTitle.trim() === '') {
        alert('Title is required');
        return;
    }

    const newDesc = prompt("Edit description:", desc || '');

    await fetch(`${API}/tasks/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            title: newTitle.trim(),
            description: newDesc || null
        })
    });

    loadTasks();
}

function escape(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

loadTasks();
</script>

</body>
</html>