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
        {
            title: 'Menu Levels',
            icon: 'MenuIcon',
            children: [{
                    title: 'Menu Level 2.1',
                    route: null,
                },
                {
                    title: 'Menu Level 2.2',
                    children: [{
                            title: 'Menu Level 3.1',
                            route: null,
                        },
                        {
                            title: 'Menu Level 3.2',
                            route: null,
                        },
                    ],
                },
            ],
        },
        {
            title: 'Disabled Menu',
            route: null,
            icon: 'EyeOffIcon',
            disabled: true,
        },
        {
            title: 'Raise Support',
            href: 'https://pixinvent.ticksy.com/',
            icon: 'LifeBuoyIcon',
        },
        {
            title: 'Documentation',
            href: 'https://pixinvent.com/demo/vuexy-vuejs-admin-dashboard-template/documentation',
            icon: 'FileTextIcon',
        },
    ],
}, ]