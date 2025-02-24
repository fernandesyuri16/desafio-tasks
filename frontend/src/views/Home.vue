<template>
  <div id="home">
    <div class="container">
      <button @click="handleLogout" class="logout-btn">Sair</button>

      <header class="home-header">
        <h1>Gerenciador de Tarefas</h1>
        <p>Organize suas tarefas e acompanhe seu progresso.</p>
      </header>

      <section class="stats">
        <div class="stat-card">
          <h3>{{ totalTasks }}</h3>
          <p>Total de Tarefas</p>
        </div>
        <div class="stat-card">
          <h3>{{ pendingTasks }}</h3>
          <p>Pendentes</p>
        </div>
        <div class="stat-card">
          <h3>{{ inProgressTasks }}</h3>
          <p>Em Andamento</p>
        </div>
        <div class="stat-card">
          <h3>{{ completedTasks }}</h3>
          <p>Concluídas</p>
        </div>
      </section>

      <section class="quick-actions">
        <router-link to="/tasks" class="btn primary">Gerenciar Tarefas</router-link>
      </section>
    </div>
  </div>
</template>

<script setup>
  import { ref, onMounted } from "vue";
  import { useRouter } from "vue-router";
  import { api } from '@/main';
  import { HTTP_STATUS } from '@/constants/httpStatus';

  const router = useRouter();

  // Estados das tarefas
  const tasks = ref([]);
  const totalTasks = ref(0);
  const pendingTasks = ref(0);
  const inProgressTasks = ref(0);
  const completedTasks = ref(0);

  // Verificar se o usuário está autenticado antes de carregar as tarefas
  const checkAuth = async () => {
    try {
      const response = await api.get("/tasks");

      if (response.status !== HTTP_STATUS.OK) {
        throw new Error("Usuário não autenticado!");
      }
    } catch (error) {
      console.error("Usuário não autenticado", error);
      router.push("/");
    }
  };

  // Buscar tarefas do backend
  const fetchTasks = async () => {
    try {
      const response = await api.get("/tasks");
      tasks.value = response.data;
      updateTaskStats();
    } catch (error) {
      console.error("Erro ao buscar tarefas", error);
    }
  };

  // Atualizar as estatísticas das tarefas
  const updateTaskStats = () => {
    totalTasks.value = tasks.value.length;
    pendingTasks.value = tasks.value.filter(task => task.status === "pending").length;
    inProgressTasks.value = tasks.value.filter(task => task.status === "in_progress").length;
    completedTasks.value = tasks.value.filter(task => task.status === "completed").length;
  };

  // Fazer logout
  const handleLogout = async () => {
    try {
      await api.post("/logout");
      router.push("/");
    } catch (error) {
      console.error("Erro ao fazer logout", error);
    }
  };

  // Executa na montagem do componente
  onMounted(async () => {
    await checkAuth();
    await fetchTasks();
  });
</script>

<style scoped>
  #home {
    display: flex;
    justify-content: center;
    align-items: center;
    text-align: center;
  }

  .container {
    width: 100%;
    background: white;
    color: #333;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    position: relative;
  }

  /* Botão de Logout */
  .logout-btn {
    position: absolute;
    top: 15px;
    right: 15px;
    background: #d9534f;
    color: white;
    border: none;
    padding: 10px 15px;
    cursor: pointer;
    border-radius: 5px;
    font-size: 14px;
  }

  .logout-btn:hover {
    background: #c9302c;
  }

  .home-header h1 {
    font-size: 28px;
    color: #782dc8;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .home-header p {
    font-size: 16px;
    color: #555;
  }

  .stats {
    display: flex;
    justify-content: space-between;
    margin: 20px;
  }

  .stat-card {
    background: #f4f4f4;
    padding: 15px;
    border-radius: 8px;
    text-align: center;
    flex: 1;
    margin: 0 5px;
  }

  .stat-card h3 {
    font-size: 22px;
    font-weight: bold;
    color: #782dc8;
  }

  .stat-card p {
    font-size: 14px;
    color: #555;
    white-space: nowrap;
  }

  .quick-actions {
    margin-top: 20px;
  }

  .btn {
    display: inline-block;
    padding: 10px 15px;
    margin: 5px;
    text-decoration: none;
    border-radius: 5px;
    font-size: 16px;
    transition: 0.3s;
    font-weight: bold;
  }

  .primary {
    background: #782dc8;
    color: white;
  }

  .primary:hover {
    background: #ddd;
    color: #782dc8;
  }
</style>
