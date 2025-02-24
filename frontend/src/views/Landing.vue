<template>
  <div id="landing">
    <header class="header">
      <h1>Bem-vindo ao Gerenciador de Tarefas</h1>
      <p>Organize suas tarefas de forma simples e eficiente!</p>
      <router-link to="/auth" class="btn primary">Entrar</router-link>
      <router-link to="/auth?mode=register" class="btn secondary">Criar Conta</router-link>
    </header>

    <section class="features">
      <div class="feature">
        <i class="fa-solid fa-clipboard"></i>
        <h3>Gerencie suas Tarefas</h3>
        <p>Crie, edite e conclua tarefas facilmente.</p>
      </div>
      <div class="feature">
        <i class="fa-solid fa-clock"></i>
        <h3>Acompanhe seu Tempo</h3>
        <p>Veja o progresso das suas atividades em tempo real.</p>
      </div>
      <div class="feature">
        <i class="fa-solid fa-calendar"></i>
        <h3>Mantenha-se Organizado</h3>
        <p>Tenha suas tarefas na palma da mão!</p>
      </div>
    </section>

    <section class="call-to-action">
      <h2>Comece a organizar suas tarefas agora mesmo!</h2>
      <router-link to="/auth?mode=register" class="btn primary">Criar Conta Gratuitamente</router-link>
    </section>
  </div>
</template>

<script setup>
</script>

<style scoped>
  #landing {
    text-align: center;
    font-family: 'Work Sans', sans-serif;
  }

  .header {
    color: white;
    padding: 30px 10px;
    background: #782dc8;
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
  }

  .header h1 {
    font-size: 25px;
    font-weight: bold;
  }

  .header p {
    font-size: 15px;
    margin-bottom: 20px;
  }

  .btn {
    display: inline-block;
    padding: 5px 10px;
    margin: 10px;
    font-weight: bold;
    text-decoration: none;
    border-radius: 5px;
    font-size: 15px;
    transition: 0.3s;
  }

  .primary {
    background: white;
    color: #782dc8;
    border: 2px solid white;
  }

  .primary:hover {
    background: #782dc8;
    color: white;
  }

  .secondary {
    background: transparent;
    border: 2px solid white;
    color: white;
  }

  .secondary:hover {
    background: white;
    font-weight: bold;
    color: #782dc8;
  }

  .features {
    display: flex;
    justify-content: space-around;
    padding: 25px 10px;
    color: black;
    background: white;
  }

  .feature {
    text-align: center;
    width: 30%;
  }

  .feature i {
    font-size: 50px;
    color: #782dc8;
    margin-bottom: 15px;
  }

  .feature h3 {
    font-size: 20px;
    margin-bottom: 10px;
  }

  .feature p {
    font-size: 13px;
    color: #555;
  }

  .call-to-action {
    background: #782dc8;
    color: white;
    padding: 25px 10px;
    border-bottom-left-radius: 15px;
    border-bottom-right-radius: 15px;
    margin-bottom: 10%;
  }

  .call-to-action h2 {
    font-size: 20px;
    margin-bottom: 20px;
  }

  .call-to-action .btn {
    background: white;
    color: #782dc8;
  }

  .call-to-action .btn:hover {
    background: #782dc8;
    color: white;
  }

</style>
