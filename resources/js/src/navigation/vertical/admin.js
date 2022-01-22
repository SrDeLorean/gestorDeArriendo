export default [{
        title: 'Bienvenida',
        icon: 'HomeIcon',
        tagVariant: 'light-warning',
        children: [{
            title: 'eCommerce-admin',
            route: 'dashboard-admin-ecommerce',
            icon: 'ShoppingCartIcon',

            resource: 'admin',
            action: 'read',

        }, ],
    },
    {
        title: 'Usuarios',
        icon: 'UserIcon',
        children: [{
                title: 'Lista',
                route: 'user-index',
                resource: 'admin',
                action: 'read',
            },
            {
                title: 'Ver',
                route: { name: 'user-show', params: { id: 21 } },
                resource: 'admin',
                action: 'read',
            },
            {
                title: 'Editar',
                route: { name: 'user-edit', params: { id: 21 } },
                resource: 'admin',
                action: 'read',
            },
        ],
    },

    {
        header: 'Other routes',
    },
    {
        title: 'Access Control-admin',
        route: 'access-control-admin',
        icon: 'ShieldIcon',
        resource: 'admin',
        action: 'read',
    },
]