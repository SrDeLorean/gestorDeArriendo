export default [{
        path: '/access-control-client',
        name: 'access-control-client',
        component: () =>
            import ('@/views/extensions/acl/AccessControl.vue'),
        meta: {
            resource: 'client',
            action: 'read',
        },
    },
    {
        path: '/dashboard-client/ecommerce',
        name: 'dashboard-client-ecommerce',
        meta: {
            resource: 'client',
            action: 'read',
        },
        component: () =>
            import ('@/views/dashboard/ecommerce/Ecommerce.vue'),
    },
    {
        path: '/reserva',
        name: 'reserva-index',
        meta: {
            resource: 'client',
            action: 'read',
        },
        component: () =>
            import ('@/views/reserva/reserva-index/ReservaIndex.vue'),
    },
    {
        path: '/reserva-create',
        name: 'reserva-create',
        meta: {
            resource: 'client',
            action: 'read',
        },
        component: () =>
            import ('@/views/reserva/reserva-create/ReservaCreate.vue'),
    },
    {
        path: '/reserva/show/:id',
        name: 'reserva-show',
        meta: {
            resource: 'client',
            action: 'read',
        },
        component: () =>
            import ('@/views/reserva/reserva-show/ReservaShow.vue'),
    },
]