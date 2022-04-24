import Vue from 'vue';
import VueRouter from 'vue-router';

import Schedule from './pages/Schedule.vue';
import Teams from './pages/Teams.vue';
import Simulation from './pages/Simulation.vue';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    linkExactActiveClass: 'active',
    routes: [
        {
            path: '/',
            name: 'teams',
            component: Teams
        },
        {
            path: '/schedule',
            name: 'schedule',
            component: Schedule
        },
        {
            path: '/simulation',
            name: 'simulation',
            component: Simulation
        },
    ]
});

export default router;
