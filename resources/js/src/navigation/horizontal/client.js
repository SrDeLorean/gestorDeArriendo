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
        {
            title: 'reserva lista',
            route: 'reserva-index',
            icon: 'ShoppingCartIcon',

            resource: 'client',
            action: 'read',

        },
        {
            title: 'reserva create',
            route: 'reserva-create',
            icon: 'ShoppingCartIcon',

            resource: 'client',
            action: 'read',

        },
        {
            title: 'reserva show',
            route: 'reserva-show',
            icon: 'ShoppingCartIcon',

            resource: 'client',
            action: 'read',

        },
    ],
}, ]