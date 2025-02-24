<template>
    <div id="app">
      <article>
        <div class="container" :class="{ 'sign-up-active': signUp }">
          <div class="overlay-container">
            <div class="overlay">
              <div class="overlay-left">
                <h2>Olá</h2>
                <p>Caso já possua uma conta, <br> faça login!</p>
                <button class="invert" @click="toggleSignUp(false)">Entrar</button>
              </div>
              <div class="overlay-right">
                <h2>Olá, bem vindo!</h2>
                <p>Caso não tenha uma conta, <br> cadastre-se agora!</p>
                <button class="invert" @click="toggleSignUp(true)">Cadastrar</button>
              </div>
            </div>
          </div>

          <form class="sign-up" @submit.prevent="handleRegister">
            <h2>Cadastrar</h2>
            <div>Insira seus dados</div>
            <input type="text" v-model="name" placeholder="Nome" required />
            <input type="email" v-model="email" placeholder="E-mail" required />
            <input type="password" v-model="password" placeholder="Senha" required />
            <button type="submit">Cadastrar</button>
          </form>

          <form class="sign-in" @submit.prevent="handleLogin">
            <h2>Entrar</h2>
            <div>Insira seus dados</div>
            <input type="email" v-model="email" placeholder="E-mail" required />
            <input type="password" v-model="password" placeholder="Senha" required />
            <button type="submit">Entrar</button>
          </form>
        </div>
        <div v-if="showError" class="error-popup">
            <p>{{ errorMessage }}</p>
        </div>
        <div v-if="showSuccess" class="success-popup">
            <p>{{ successMessage }}</p>
        </div>
      </article>
    </div>
</template>

<script setup>
    import { ref, onMounted } from 'vue';
    import { useRoute, useRouter } from 'vue-router';
    import { api } from '@/main';
    import { HTTP_STATUS } from '@/constants/httpStatus';
    import Cookies from "js-cookie";

    const route = useRoute();
    const router = useRouter();

    // Estados reativos
    const signUp = ref(false);
    const email = ref('');
    const password = ref('');
    const name = ref('');
    const errorMessage = ref("");
    const successMessage = ref("");
    const showError = ref(false);
    const showSuccess = ref(false);


    // Alternar entre Login e Cadastro
    const toggleSignUp = (value) => {
        signUp.value = value;
    };

    onMounted(() => {
        if (route.query.mode === 'register') {
            signUp.value = true;
        }
    });

    // Autenticação com a API
    const handleLogin = async () => {
        try {
            const response = await api.post('/login', {
                email: email.value,
                password: password.value
            });

            if (response.status === HTTP_STATUS.CREATED) {
                const token = response.data.token;

                localStorage.setItem('authToken', token);
                Cookies.set('token', token, {expires:7});
                api.defaults.headers.common['Authorization'] = `Bearer ${token}`;

                showsSuccessPopup('Login realizado com sucesso!');
                router.push('/home');
            } else {
                showErrorPopup('Falha na autenticação.');
            }
        } catch (error) {
            showErrorPopup('Verifique suas credenciais!');
            console.error(error);
        }
    };

    const handleRegister = async () => {
        try {
            const response = await api.post('/users', {
                name: name.value,
                email: email.value,
                password: password.value
            });

            if (response.status === HTTP_STATUS.CREATED) {
                showsSuccessPopup('Cadastro realizado com sucesso!');
                signUp.value = false; // Alternar para login
            } else {
                showErrorPopup('Erro ao cadastrar. Tente novamente.');
            }
        } catch (error) {
            showErrorPopup('Erro ao cadastrar. Verifique os dados e tente novamente.');
            console.error(error);
        }
    };

    const showErrorPopup = (message) => {
        errorMessage.value = message;
        showError.value = true;

        setTimeout(() => {
            showError.value = false;
        }, 3000);
    };

    const showsSuccessPopup = (message) => {
        successMessage.value = message;
        showSuccess.value = true;

        setTimeout(() => {
            showSuccess.value = false;
        }, 3000);
    };
</script>

<style scoped>
    body {
        margin: 0;
        padding: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    #app {
        font-size: 20px;
        color: #222;
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .container {
        position: relative;
        width: 1000px;
        height: 480px;
        border-radius: 10px;
        overflow: hidden;
        background: linear-gradient(to bottom, #efefef, #ccc);
    }

    .overlay-container {
        position: absolute;
        top: 0;
        left: 50%;
        width: 50%;
        height: 100%;
        overflow: hidden;
        transition: transform .5s ease-in-out;
        z-index: 100;
    }

    .overlay {
        position: relative;
        left: -100%;
        height: 100%;
        width: 200%;
        background: #782dc8;
        color: #fff;
        transform: translateX(0);
        transition: transform .5s ease-in-out;
    }

    .overlay-left, .overlay-right {
        position: absolute;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: center; /* Centraliza os textos horizontalmente */
        flex-direction: column;
        padding: 70px 40px;
        width: 50%;
        height: 100%;
        text-align: center;
        transition: transform 0.5s ease-in-out;
    }


    .overlay-left {
        transform: translateX(-100%);
    }

    .overlay-right {
        right: 0;
    }

    p {
        margin: 20px 0 30px;
    }

    button {
        border-radius: 5px;
        border: 1px solid #782dc8;
        background-color: #782dc8;
        color: #fff;
        font-size: 1rem;
        font-weight: bold;
        padding: 10px 40px;
        margin: 5%;
        letter-spacing: 1px;
        text-transform: uppercase;
        cursor: pointer;
        transition: transform .1s ease-in;
    }

    button:active {
        transform: scale(.9);
    }

    button.invert {
        background-color: transparent;
        border-color: #ddd;
    }

    button:hover {
        background-color: #b6b6b6;
        border: 2px solid #b6b6b6;
        color: #782dc8;
    }

    form {
        position: absolute;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: space-around;
        flex-direction: column;
        padding: 90px 60px;
        width: calc(50% - 120px);
        height: calc(100% - 180px);
        text-align: center;
        background: linear-gradient(to bottom, #efefef, #ccc);
        transition: all .5s ease-in-out;
    }

    form div {
        font-size: 15px;
    }

    form input {
        background-color: #eee;
        border: none;
        font-family: 'Work Sans', sans-serif;
        font-size: 15px;
        padding: 10px 15px;
        margin: 6px 0;
        width: 100%;
        border-radius: 5px;
        border-bottom: 3px solid #782dc8;
    }

    form input:focus {
        outline: none;
        background-color: #fff;
    }

    .sign-in {
        left: 0;
        z-index: 2;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 50%;
        padding: 10%;
        height: 100%;
        position: absolute;
        right: 0;
    }

    .sign-up {
        left: 0;
        z-index: 1;
        opacity: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        width: 50%;
        padding: 10%;
        height: 100%;
        position: absolute;
        right: 0;
    }

    .sign-up-active .sign-in {
        transform: translateX(100%);
        opacity: 0;
        z-index: 0;
    }

    .sign-up-active .sign-up {
        transform: translateX(100%);
        opacity: 1;
    }

    .sign-up-active .overlay-container {
        transform: translateX(-100%);
    }

    .sign-up-active .overlay {
        transform: translateX(50%);
    }

    .sign-up-active .overlay-left {
        transform: translateX(0);
    }

    .sign-up-active .overlay-right {
        transform: translateX(100%);
    }

    @keyframes show {
        0% {
            opacity: 0;
            z-index: 1;
        }
        50% {
            opacity: 1;
            z-index: 10;
        }
    }

    .error-popup {
        color: red;
        font-family: 'Work Sans', sans-serif;
        font-weight: bold;
    }

    .success-popup {
        color: lightgreen;
        font-family: 'Work Sans', sans-serif;
        font-family: bold;
    }
</style>
