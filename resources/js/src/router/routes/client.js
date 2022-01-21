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
]