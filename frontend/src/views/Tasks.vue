<template>
  <div id="tasks">
    <div class="container">
      <router-link to="/home" class="back-btn">
        <i class="pi pi-arrow-left"></i>
        Voltar
      </router-link>

      <header class="task-header">
        <h1>Gerenciar Tarefas</h1>
        <p>Organize suas tarefas e acompanhe seu progresso.</p>
      </header>

      <form @submit.prevent="addTask" class="task-form">
        <input type="text" v-model="newTaskTitle" placeholder="Título da tarefa..." required />
        <input type="text" v-model="newTaskDescription" placeholder="Descrição..." required />
        <select v-model="newTaskStatus">
          <option value="pending">Pendente</option>
          <option value="in_progress">Em andamento</option>
          <option value="completed">Concluída</option>
        </select>
        <input type="date" v-model="newTaskDueDate" :min="minDate" required />
        <button type="submit" class="btn primary">Adicionar</button>
      </form>

      <div v-if="tasks.length === 0" class="empty-task">
        <p>Nenhuma tarefa cadastrada ainda.</p>
        <p>Adicione sua primeira tarefa agora!</p>
      </div>

      <ul v-if="tasks.length > 0" class="task-list">
        <li v-for="task in tasks" :key="task.id" class="task-item">
          <div v-if="editingTaskId !== task.id">
            <h3>{{ task.title }}</h3>
            <p>{{ task.description }}</p>
            <p><strong>Status:</strong> {{ getStatusLabel(task.status) }}</p>
            <p><strong>Prazo:</strong> {{ formatDate(task.due_date) }}</p>
            <button @click="editTask(task)" class="btn secondary">Editar</button>
            <button @click="confirmDelete(task.id)" class="btn danger">Excluir</button>
          </div>

          <div v-else class="edit-task-form">
            <input type="text" v-model="editedTask.title" required />
            <input type="text" v-model="editedTask.description" required />
            <select v-model="editedTask.status">
              <option value="pending">Pendente</option>
              <option value="in_progress">Em andamento</option>
              <option value="completed">Concluída</option>
            </select>
            <input type="date" v-model="editedTask.due_date" required />
            <button @click="saveTask(task.id)" class="btn primary">Salvar</button>
            <button @click="cancelEdit" class="btn secondary">Cancelar</button>
          </div>
        </li>
      </ul>
    </div>
    <div v-if="showError" class="error-popup">
      <p>{{ errorMessage }}</p>
    </div>
    <div v-if="showSuccess" class="success-popup">
      <p>{{ successMessage }}</p>
    </div>
    <div v-if="showDeleteConfirm" class="alert-overlay">
    <div class="alert-box">
      <h2>Confirmação</h2>
      <p>Tem certeza que deseja excluir esta tarefa?</p>
      <div class="alert-actions">
        <button @click="deleteTask()" class="btn danger">Sim, excluir</button>
        <button @click="showDeleteConfirm = false" class="btn secondary">Cancelar</button>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from "vue";
  import { api } from "@/main";

  const tasks = ref([]);
  const newTaskTitle = ref("");
  const newTaskDescription = ref("");
  const newTaskStatus = ref("pending");
  const newTaskDueDate = ref("");
  const errorMessage = ref("");
  const successMessage = ref("");
  const showError = ref(false);
  const showSuccess = ref(false);
  const showDeleteConfirm = ref(false);
  const taskToDelete = ref(null);

  // Estado de edição
  const editingTaskId = ref(null);
  const editedTask = ref({});

  const minDate = ref(new Date().toISOString().split("T")[0]);

  // Exibir error
  const showErrorPopup = (message) => {
    errorMessage.value = message;
    showError.value = true;
    setTimeout(() => {
      showError.value = false;
    }, 5000);
  };

  // Exibir success
  const showsSuccessPopup = (message) => {
      successMessage.value = message;
      showSuccess.value = true;

      setTimeout(() => {
          showSuccess.value = false;
      }, 3000);
  };

  // Buscar tarefas
  const fetchTasks = async () => {
    try {
      const response = await api.get("tasks");
      tasks.value = response.data.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    } catch (error) {
      showErrorPopup("Erro ao buscar tarefas.");
    }
  };

  // Adicionar tarefa
  const addTask = async () => {
    try {
      const newTask = {
        title: newTaskTitle.value,
        description: newTaskDescription.value,
        status: newTaskStatus.value,
        due_date: newTaskDueDate.value,
      };

      const response = await api.post("tasks", newTask);
      await fetchTasks();

      // Resetar os campos
      newTaskTitle.value = "";
      newTaskDescription.value = "";
      newTaskDueDate.value = "";
    } catch (error) {
      showErrorPopup("Erro ao adicionar tarefa.");
    }
  };

  // Função para abrir o modal de confirmação
  const confirmDelete = (taskId) => {
    taskToDelete.value = taskId;
    showDeleteConfirm.value = true;
  };

  // Excluir tarefa
  const deleteTask = async () => {
    if (!taskToDelete.value) return;

    try {
      await api.delete(`tasks/${taskToDelete.value}`);
      tasks.value = tasks.value.filter((task) => task.id !== taskToDelete.value);
      showsSuccessPopup("Tarefa excluída com sucesso!");

      taskToDelete.value = null;
      showDeleteConfirm.value = false;
    } catch (error) {
      showErrorPopup("Erro ao excluir tarefa!");
      console.error(error);
    }
  };

  // Editar tarefa
  const editTask = (task) => {
    editingTaskId.value = task.id;
    editedTask.value = { ...task };
  };

  // Salvar edição
  const saveTask = async (taskId) => {
    try {
      await api.put(`tasks/${taskId}`, editedTask.value);
      const index = tasks.value.findIndex((task) => task.id === taskId);
      tasks.value[index] = { ...editedTask.value };
      editingTaskId.value = null;
    } catch (error) {
      console.log(error)
      showErrorPopup("Erro ao atualizar tarefa.");
    }
  };

  // Cancelar edição
  const cancelEdit = () => {
    editingTaskId.value = null;
  };

  // Formatar status
  const getStatusLabel = (status) => {
    const statusMap = {
      pending: "Pendente",
      in_progress: "Em andamento",
      completed: "Concluída",
    };
    return statusMap[status] || "Desconhecido";
  };

  // Formatar data
  const formatDate = (date) => {
    if (!date) return "Sem data";
    return new Date(date).toLocaleDateString("pt-BR");
  };

  onMounted(fetchTasks);
</script>

<style scoped>
  #tasks {
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .container {
    max-width: 900px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
  }

  /* Botão de Voltar */
  .back-btn {
    display: block;
    text-align: left;
    margin-bottom: 15px;
    color: #a100f2;
    font-weight: bold;
    text-decoration: none;
    font-size: 16px;
    width: 10%;
  }

  .back-btn:hover {
    background: none;
  }

  /* Cabeçalho */
  .task-header h1 {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 5px;
    color: white;
  }

  .task-header p {
    font-size: 14px;
    color: gray;
    margin-bottom: 20px;
  }

  .empty-task {
    margin-top: 20px;
    font-size: 14px;
    color: gray;
  }

  .task-form {
    display: flex;
    gap: 10px;
    justify-content: center;
  }

  .task-form input,
  .task-form select {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 16px;
    width: 50%;
    font-family: 'Work Sans', sans-serif;
  }

  .task-form input:focus,
  .task-form select:focus {
    outline: none;
    border: 1px solid #a100f2;
  }

  .task-form button {
    background: #a100f2;
    color: white;
    padding: 12px 20px;
    border-radius: 10px;
    font-size: 16px;
    border: none;
    cursor: pointer;
    transition: 0.3s;
    width: auto;
    font-family: 'Work Sans', sans-serif;
  }

  .task-list {
    max-height: 400px;
    overflow-y: auto;
    padding: 10px;
    margin-top: 20px;
    margin-bottom: 50px;
    background: #f9f9f9;
    border-radius: 10px;
  }

  .task-item {
    background: #f9f9f9;
    border-radius: 10px;
    padding-inline: 15px;
    padding-bottom: 15px;
    text-align: left;
  }

  .task-item h3 {
    color: black;
    font-weight: bold;
  }

  .task-item p {
    margin: 3px 0;
    font-size: 14px;
    color: gray;
  }

  .task-item button {
    margin-right: 5px;
  }

  .btn {
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 14px;
    border: none;
    font-family: 'Work Sans', sans-serif;
    cursor: pointer;
    transition: 0.3s;
  }

  .btn.primary {
    background: #a100f2;
    color: white;
  }

  .btn.primary:hover {
    background: #782dc8;
  }

  .btn.secondary {
    background: #ddd;
    color: black;
  }

  .btn.secondary:hover {
    background: #bbb;
  }

  .btn.danger {
    background: #ff0044;
    color: white;
  }

  .btn.danger:hover {
    background: #cc0033;
  }

  .edit-task-form input,
  .edit-task-form select {
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    font-size: 16px;
    width: 48%;
    font-family: 'Work Sans', sans-serif;
  }

  .edit-task-form {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    justify-content: flex-start;
  }

  .error-popup {
      color: red;
      font-family: 'Work Sans', sans-serif;
      font-weight: bold;
  }

  .success-popup {
    color: lightgreen;
    font-family: 'Work Sans', sans-serif;
    font-weight: bold;
  }

  .alert-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
  }

  .alert-box {
    background: white;
    padding: 25px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.3);
    max-width: 400px;
    width: 90%;
    animation: fadeIn 0.3s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: scale(0.9);
    }
    to {
      opacity: 1;
      transform: scale(1);
    }
  }

  .alert-box h2 {
    margin-bottom: 10px;
    color: black;
    font-weight: bold;
  }

  .alert-box p {
    color: grey;
  }

  .alert-actions {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 15px;
  }

  .alert-actions .btn {
    padding: 10px 15px;
    font-size: 16px;
    border-radius: 8px;
    min-width: 100px;
    transition: 0.2s ease-in-out;
  }

  .alert-actions .btn:hover {
    transform: scale(1.05);
  }
</style>
