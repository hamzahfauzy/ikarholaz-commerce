<?php

$menu = [];

// Dashboard
$menu[] = [
    'label' => 'Dashboard',
    'icon'  => 'mdi mdi-view-dashboard',
    'route' => 'staff.index',
    'childs' => []
];

$menu[] = [
    'label' => 'Categories',
    'icon'  => 'mdi mdi-layers',
    'route' => 'staff.categories.index',
    'childs' => [
        [
            'label' => 'All Data',
            'route' => 'staff.categories.index',
            'childs' => []
        ],
        [
            'label' => 'Add New',
            'route' => 'staff.categories.create',
            'childs' => []
        ]
    ]
];

$menu[] = [
    'label' => 'Products',
    'icon'  => 'mdi mdi-diamond-stone',
    'route' => 'staff.products.index',
    'childs' => [
        [
            'label' => 'All Data',
            'route' => 'staff.products.index',
            'childs' => []
        ],
        [
            'label' => 'Add New',
            'route' => 'staff.products.create',
            'childs' => []
        ]
    ]
];

$menu[] = [
    'label' => 'Customers',
    'icon'  => 'mdi mdi-account-multiple',
    'route' => 'staff.customers.index',
    'childs' => [
        [
            'label' => 'All Data',
            'route' => 'staff.customers.index',
            'childs' => []
        ],
        [
            'label' => 'Add New',
            'route' => 'staff.customers.create',
            'childs' => []
        ]
    ]
];

$menu[] = [
    'label' => 'Transactions',
    'icon'  => 'mdi mdi-google-pages',
    'route' => 'staff.transactions.index',
    'childs' => []
];

$menu[] = [
    'label' => 'Payments',
    'icon'  => 'mdi mdi-credit-card',
    'route' => 'staff.payments.index',
    'childs' => []
    //     [
    //         'label' => 'All Data',
    //         'route' => 'staff.payments.index',
    //         'childs' => []
    //     ],
    //     [
    //         'label' => 'Add New',
    //         'route' => 'staff.payments.create',
    //         'childs' => []
    //     ],
    //     [
    //         'label' => 'Payment Methods',
    //         'route' => 'staff.payments.create',
    //         'childs' => []
    //     ]
    // ]
];


$menu[] = [
    'label' => 'Cards',
    'icon'  => 'mdi mdi-account-card-details',
    'route' => 'staff.cards.index',
    'childs' => [
        [
            'label' => 'All Data',
            'route' => 'staff.cards.index',
            'childs' => []
        ],
        [
            'label' => 'Add New',
            'route' => 'staff.cards.create',
            'childs' => []
        ],
        // [
        //     'label' => 'Setting',
        //     'route' => 'staff.cards.setting',
        //     'childs' => []
        // ]
    ]
];

$menu[] = [
    'label' => 'Alumni',
    'icon'  => 'mdi mdi-account',
    'route' => 'staff.alumnis.index',
    'childs' => [
        [
            'label' => 'All Data',
            'route' => 'staff.alumnis.index',
            'childs' => []
        ],
        [
            'label' => 'Add New',
            'route' => 'staff.alumnis.create',
            'childs' => []
        ],
        [
            'label' => 'Import',
            'route' => 'staff.alumnis.import',
            'childs' => []
        ],
    ]
    //     [
    //         'label' => 'All Data',
    //         'route' => 'staff.payments.index',
    //         'childs' => []
    //     ],
    //     [
    //         'label' => 'Add New',
    //         'route' => 'staff.payments.create',
    //         'childs' => []
    //     ],
    //     [
    //         'label' => 'Payment Methods',
    //         'route' => 'staff.payments.create',
    //         'childs' => []
    //     ]
    // ]
];

// $menu[] = [
//     'label' => 'Custom Fields',
//     'icon'  => 'mdi mdi-format-list-bulleted',
//     'route' => 'staff.custom-fields.index',
//     'childs' => [
//         [
//             'label' => 'All Data',
//             'route' => 'staff.custom-fields.index',
//             'childs' => []
//         ],
//         [
//             'label' => 'Add New',
//             'route' => 'staff.custom-fields.create',
//             'childs' => []
//         ]
//     ]
// ];

return $menu;
