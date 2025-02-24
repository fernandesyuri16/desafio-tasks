<template>
    <div id="not-found">
      <header class="header">
        <h1>Epa! Página não encontrada</h1>
        <p>A página que você está procurando não existe ou foi descontinuada.</p>
        <router-link to="/" class="btn primary">Voltar para o Início</router-link>
      </header>

      <section class="features">
        <div class="feature">
          <i class="pi pi-exclamation-triangle"></i>
          <h3>Página Não Encontrada</h3>
          <p>Verifique o endereço digitado ou volte para a página inicial.</p>
        </div>
        <div class="feature">
          <i class="fa-solid fa-question-circle"></i>
          <h3>Precisa de Ajuda?</h3>
          <p>Entre em contato com nosso suporte para mais informações.</p>
        </div>
        <div class="feature">
          <i class="pi pi-home"></i>
          <h3>Volte para o Início</h3>
          <p>Retorne e continue gerenciando suas tarefas!</p>
        </div>
      </section>
    </div>
  </template>

<style scoped>
    #not-found {
        text-align: center;
        font-family: 'Work Sans', sans-serif;
    }

    /* Cabeçalho */
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
</style>
