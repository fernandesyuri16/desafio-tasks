import { createRouter, createWebHistory } from 'vue-router';
import NotFound from '@/views/NotFound.vue';
import Auth from '@/views/Auth.vue';
import Home from '@/views/Home.vue';
import Landing from '@/views/Landing.vue';
import TaskList from '@/views/Tasks.vue';
import { HTTP_STATUS } from '@/constants/httpStatus';


const routes = [
    { path: '/:pathMatch(.*)*', component: NotFound },
    { path: '/', component: Landing, meta: { title: 'Desafio Tasks' }},
    { path: '/home', component: Home, meta: { title: 'Home' }},
    { path: '/auth', component: Auth, meta: { title: 'Entrar' }},
    { path: '/tasks', component: TaskList, meta: { title: 'Tarefas'}},
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach(async (to, from, next) => {
    if (to.path === '/auth' && from.meta.requiresAuth) {
        next('/home');
    }

    if (to.meta.requiresAuth) {
        try {
            const response = await api.get('/tasks');
            if (response.status === HTTP_STATUS.OK) {
                next();
            } else {
                next('/');
            }
        } catch (error) {
            next('/');
        }
    } else {
        next();
    }
});

export default router;
