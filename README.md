# 👨🏻‍💻  **Desafio Tasks**

Este projeto consiste em uma API RESTful para gerenciar  tarefas onde os usuários podem
adicionar, visualizar, editar e excluir tarefas e estes são alguns recursos:

1. Criação de usuário, login e logout;
2. Listagem, criação, edição e exclusão de tarefas;
3. Insights mostrando total de tarefas, pendentes, em andamento e concluídas.
4. Autenticação para lidar com planos de férias e usuários;

A pasta no diretório contém o projeto desenvolvido em Laravel, Vue.js e Docker.


## 💻 **Project setup**

### 🛫 Início

Para começar, primeiro precisamos inicializar os contêineres do Docker executando o seguinte comando na **raiz** do projeto:
```
sudo make run
```
Este comando será responsável por:

1. Copiar os arquivos .env.example para .env, tanto no projeto principal quanto no backend, garantindo que as variáveis de ambiente estejam configuradas.
2. Reiniciar os containers Docker, removendo (down), reconstruindo (build) e subindo (up -d) novamente o ambiente.
3. Instalar dependências no backend, ajusta permissões e executa migrations + seeders no banco de dados.
4. Instalar dependências no frontend, compila o projeto (npm install && npm run build) e o deixa pronto para uso!

Tudo pronto! Se estiver tudo certo, a visualização do frontend estará disponível na rota `localhost:8001`.

**📱 Frontend**
| Rota | Descrição |
|---------|-----------|
| `/`     | Landing page |
| `/auth` | Autenticação de usuário |
| `/home` | Página inicial contendo insights das tasks |
| `/tasks`| Interface de manipulação das tasks |

## 🔧 Finalização

Para finalizar o projeto, é necessário encerrar todos os contêineres que foram iniciados no início.

Finalizando os contêineres executando o seguinte comando na raiz do projeto:
```
sudo make stop
```
Observação: se você receber um erro na primeira inicialização, encerre o aplicativo e inicie-o novamente. Ou se necessário, entre em contato comigo!

## 🚨 **Spoiler**

### Autenticação
![image](https://github.com/user-attachments/assets/f3e1063f-0c4a-4dd2-95f0-fc89c2207f02)

### Landing
![image](https://github.com/user-attachments/assets/b8dea31a-4274-4619-a476-d6e04a0c03af)

### Tarefas
![image](https://github.com/user-attachments/assets/aef3aedd-f842-4659-9866-f8303dd0ef39)


## 🏗️ **Contruído com**

* [PHP](https://www.php.net/)
* [Laravel](https://laravel.com/)
* [Vue.js](https://vuejs.org/)
* [Docker](https://www.docker.com/)

---
👨🏻‍💻 Developed by [Yuri Fernandes](https://github.com/fernandesyuri16)
