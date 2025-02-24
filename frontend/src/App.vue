<script setup>
  import { RouterView } from 'vue-router';
</script>

<template>
  <div class="logo-container">
    <a href="/">
      <img src="https://login.kinghost.com.br/assets/images/favicon/favicon-kinghost.svg" alt="Logo" class="logo">
    </a>
  </div>
  <RouterView />
  <a id="github_link" target="_blank" href="https://github.com/fernandesyuri16">
    <i class="fab fa-github"></i> GitHub
  </a>
</template>

<style scoped>
  html, body, #app {
    height: 100%;
    width: 100%;
  }

  .logo-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50%;
    width: 100%;
    padding-bottom: 2%;
  }

  .logo-container a:hover {
    background: none;
  }

  .logo {
    max-width: 100px;
    width: 100%;
  }
</style>
