export default [{
        header: 'Admin',
    },
    {
        title: 'Access Control-admin',
        route: 'access-control-admin',
        icon: 'ShieldIcon',
        // acl: {
        resource: 'admin',
        action: 'read',
        // },
    },
    {
        title: 'eCommerce-admin',
        route: 'dashboard-admin-ecommerce',
        icon: 'ShoppingCartIcon',

        resource: 'admin',
        action: 'read',

    },
]