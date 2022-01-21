export default [{
    header: 'Client',
    icon: 'MoreHorizontalIcon',
    children: [{
            title: 'Access Control-client',
            route: 'access-control-client',
            icon: 'ShieldIcon',
            // acl: {
            action: 'read',
            resource: 'client',
            // },
        },
        {
            title: 'eCommerce-client',
            route: 'dashboard-client-ecommerce',
            icon: 'ShoppingCartIcon',

            resource: 'client',
            action: 'read',

        },
    ],
}, ]