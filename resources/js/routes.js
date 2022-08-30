import Vue from 'vue';
import Router from 'vue-router';

Vue.use(Router);

export default new Router({
    routes: [
        // INICIO
        {
            path: '/',
            name: 'inicio',
            component: require('./components/Inicio.vue').default
        },

        // LOGIN
        {
            path: '/login',
            name: 'login',
            component: require('./Auth.vue').default
        },

        // USUARIOS
        {
            path: '/usuarios/perfil/:id',
            name: 'usuarios.perfil',
            component: require('./components/modulos/usuarios/perfil.vue').default,
            props: true
        },
        {
            path: '/usuarios',
            name: 'usuarios.index',
            component: require('./components/modulos/usuarios/index.vue').default
        },

        // USUARIOS
        {
            path: '/personals',
            name: 'personals.index',
            component: require('./components/modulos/personals/index.vue').default
        },

        // TIPO DE SOPORTES
        {
            path: '/tipo_soportes',
            name: 'tipo_soportes.index',
            component: require('./components/modulos/tipo_soportes/index.vue').default,
        },

        // TICKETS
        {
            path: '/tickets',
            name: 'tickets.index',
            component: require('./components/modulos/tickets/index.vue').default,
        },
        {
            path: '/tickets_eliminados',
            name: 'tickets.tickets_eliminados',
            component: require('./components/modulos/tickets/eliminados.vue').default,
        },
        {
            path: '/mis_tickets',
            name: 'tickets.mis_tickets',
            component: require('./components/modulos/tickets/mis_tickets.vue').default,
        },
        
        {
            path: '/tickets/create',
            name: 'tickets.create',
            component: require('./components/modulos/tickets/create.vue').default,
        },

        {
            path: '/tickets/edit/:id',
            name: 'tickets.edit',
            props: true,
            component: require('./components/modulos/tickets/edit.vue').default,
        },

        {
            path: '/tickets/incidencias/:id',
            name: 'tickets.incidencias',
            props: true,
            component: require('./components/modulos/tickets/incidencias.vue').default,
        },

        {
            path: '/tickets/incidencias/editar_incidencia/:id',
            name: 'tickets.editar_incidencia',
            props: true,
            component: require('./components/modulos/tickets/edit_solucion.vue').default,
        },

        // FAQS
        {
            path: '/faqs',
            name: 'faqs.index',
            component: require('./components/modulos/faqs/index.vue').default,
        },
        {
            path: '/faqs/lista',
            name: 'faqs.lista',
            component: require('./components/modulos/faqs/lista.vue').default,
        },

        // CONFIGURACIÓN
        {
            path: '/configuracion',
            name: 'configuracion',
            component: require('./components/modulos/configuracion/index.vue').default,
            props: true
        },
        // REPORTES
        {
            path: '/reportes/usuarios',
            name: 'reportes.usuarios',
            component: require('./components/modulos/reportes/usuarios.vue').default,
            props: true
        },
        {
            path: '/reportes/registros',
            name: 'reportes.registros',
            component: require('./components/modulos/reportes/registros.vue').default,
            props: true
        },
        {
            path: '/reportes/soluciones',
            name: 'reportes.soluciones',
            component: require('./components/modulos/reportes/soluciones.vue').default,
            props: true
        },
        {
            path: '/reportes/historial_tickets',
            name: 'reportes.historial_tickets',
            component: require('./components/modulos/reportes/historial_tickets').default,
            props: true
        },
        {
            path: '/reportes/atencion_tickets',
            name: 'reportes.atencion_tickets',
            component: require('./components/modulos/reportes/atencion_tickets').default,
            props: true
        },
        {
            path: '/reportes/estado_tickets',
            name: 'reportes.estado_tickets',
            component: require('./components/modulos/reportes/estado_tickets.vue').default,
            props: true
        },

        // PÁGINA NO ENCONTRADA
        {
            path: '*',
            component: require('./components/modulos/errors/404.vue').default
        },
    ],
    mode: 'history',
    linkActiveClass: 'active'
});