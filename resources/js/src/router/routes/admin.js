export default [{
        path: '/access-control-admin',
        name: 'access-control-admin',
        component: () =>
            import ('@/views/extensions/acl/AccessControl.vue'),
        meta: {
            resource: 'admin',
            action: 'read',
        },
    },
    {
        path: '/dashboard-admin/ecommerce',
        name: 'dashboard-admin-ecommerce',
        meta: {
            resource: 'admin',
            action: 'read',
        },
        component: () =>
            import ('@/views/dashboard/ecommerce/Ecommerce.vue'),
    },

]