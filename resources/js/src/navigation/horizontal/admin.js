export default [{
    header: 'Admin',
    icon: 'MoreHorizontalIcon',
    children: [{
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
    ],
}, ]