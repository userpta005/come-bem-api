<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission
{
    use HasFactory;

    public static function defaultPermissions()
    {
        return [
            [
                'title' => 'Painel',
                'items' => [
                    array('name' => 'panel_view', 'description' => 'Visualizar'),
                ]
            ],
            [
                'title' => 'Cadastros',
                'items' => [
                    [
                        'title' => 'Pessoas',
                        'items' => [
                            [
                                'title' => 'Leads',
                                'items' => [
                                    array('name' => 'leads_view', 'description' => 'Visualizar'),
                                    array('name' => 'leads_create', 'description' => 'Criar'),
                                    array('name' => 'leads_edit', 'description' => 'Editar'),
                                    array('name' => 'leads_delete', 'description' => 'Deletar'),
                                ]
                            ],
                            [
                                'title' => 'Contrantates',
                                'items' => [
                                    array('name' => 'tenants_view', 'description' => 'Visualizar'),
                                    array('name' => 'tenants_create', 'description' => 'Criar'),
                                    array('name' => 'tenants_edit', 'description' => 'Editar'),
                                    array('name' => 'tenants_delete', 'description' => 'Deletar'),
                                ]
                            ],
                            [
                                'title' => 'Lojas',
                                'items' => [
                                    array('name' => 'stores_view', 'description' => 'Visualizar'),
                                    array('name' => 'stores_create', 'description' => 'Criar'),
                                    array('name' => 'stores_edit', 'description' => 'Editar'),
                                    array('name' => 'stores_delete', 'description' => 'Deletar'),
                                ]
                            ],
                            [
                                'title' => 'Clientes',
                                'items' => [
                                    array('name' => 'clients_view', 'description' => 'Visualizar'),
                                    array('name' => 'clients_create', 'description' => 'Criar'),
                                    array('name' => 'clients_edit', 'description' => 'Editar'),
                                    array('name' => 'clients_delete', 'description' => 'Deletar'),
                                    [
                                        'title' => 'Dependentes',
                                        'items' => [
                                            array('name' => 'dependents_view', 'description' => 'Visualizar'),
                                            array('name' => 'dependents_create', 'description' => 'Criar'),
                                            array('name' => 'dependents_edit', 'description' => 'Editar'),
                                            array('name' => 'dependents_delete', 'description' => 'Deletar'),
                                            [
                                                'title' => 'Contas',
                                                'items' => [
                                                    array('name' => 'accounts_view', 'description' => 'Visualizar'),
                                                    array('name' => 'accounts_create', 'description' => 'Criar'),
                                                    array('name' => 'accounts_edit', 'description' => 'Editar'),
                                                    array('name' => 'accounts_delete', 'description' => 'Deletar'),
                                                    [
                                                        'title' => 'Cartões',
                                                        'items' => [
                                                            array('name' => 'cards_view', 'description' => 'Visualizar'),
                                                            array('name' => 'cards_create', 'description' => 'Criar'),
                                                            array('name' => 'cards_edit', 'description' => 'Editar'),
                                                            array('name' => 'cards_delete', 'description' => 'Deletar'),
                                                        ]
                                                    ]
                                                ]
                                            ]
                                        ]
                                    ]
                                ]
                            ],
                        ]
                    ],
                    [
                        'title' => 'Operacionais',
                        'items' => [
                            [
                                'title' => 'Produtos',
                                'items' => [
                                    array('name' => 'products_view', 'description' => 'Visualizar'),
                                    array('name' => 'products_create', 'description' => 'Criar'),
                                    array('name' => 'products_edit', 'description' => 'Editar'),
                                    array('name' => 'products_delete', 'description' => 'Deletar'),
                                ]
                            ],
                            [
                                'title' => 'Seções',
                                'items' => [
                                    array('name' => 'sections_view', 'description' => 'Visualizar'),
                                    array('name' => 'sections_create', 'description' => 'Criar'),
                                    array('name' => 'sections_edit', 'description' => 'Editar'),
                                    array('name' => 'sections_delete', 'description' => 'Deletar'),
                                ]
                            ],
                            [
                                'title' => 'Formas de Pagamento',
                                'items' => [
                                    array('name' => 'payment-methods_view', 'description' => 'Visualizar'),
                                    array('name' => 'payment-methods_create', 'description' => 'Criar'),
                                    array('name' => 'payment-methods_edit', 'description' => 'Editar'),
                                    array('name' => 'payment-methods_delete', 'description' => 'Deletar'),
                                ]
                            ],
                            [
                                'title' => 'Unidades de Medida',
                                'items' => [
                                    array('name' => 'measurement-units_view', 'description' => 'Visualizar'),
                                    array('name' => 'measurement-units_create', 'description' => 'Criar'),
                                    array('name' => 'measurement-units_edit', 'description' => 'Editar'),
                                    array('name' => 'measurement-units_delete', 'description' => 'Deletar'),
                                ]
                            ],
                        ]
                    ],
                    [
                        'title' => 'Gerais',
                        'items' => [
                            [
                                'title' => 'Cidades',
                                'items' => [
                                    array('name' => 'cities_view', 'description' => 'Visualizar'),
                                ]
                            ],
                            [
                                'title' => 'Estados',
                                'items' => [
                                    array('name' => 'states_view', 'description' => 'Visualizar'),
                                ]
                            ],
                            [
                                'title' => 'Ncms',
                                'items' => [
                                    array('name' => 'ncms_view', 'description' => 'Visualizar'),
                                ]
                            ],
                        ]
                    ],
                ]
            ],
            [
                'title' => 'Estoque',
                'items' => [
                    [
                        'title' => 'Requisições/Saída',
                        'items' => [
                            array('name' => 'requisitions_view', 'description' => 'Visualizar'),
                            array('name' => 'requisitions_create', 'description' => 'Criar'),
                            array('name' => 'requisitions_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Devolução/Entrada',
                        'items' => [
                            array('name' => 'devolutions_view', 'description' => 'Visualizar'),
                            array('name' => 'devolutions_create', 'description' => 'Criar'),
                            array('name' => 'devolutions_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Saldo Inicial',
                        'items' => [
                            array('name' => 'openingbalances_view', 'description' => 'Visualizar'),
                            array('name' => 'openingbalances_create', 'description' => 'Criar'),
                            array('name' => 'openingbalances_edit', 'description' => 'Editar'),
                            array('name' => 'openingbalances_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Saldo Estoque',
                        'items' => [
                            array('name' => 'stocks_view', 'description' => 'Visualizar'),
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Financeiro',
                'items' => [
                    [
                        'title' => 'Gerais',
                        'items' => [
                            [
                                'title' => 'Categorias Financeira',
                                'items' => [
                                    array('name' => 'financialcategories_view', 'description' => 'Visualizar'),
                                    array('name' => 'financialcategories_create', 'description' => 'Criar'),
                                    array('name' => 'financialcategories_edit', 'description' => 'Editar'),
                                    array('name' => 'financialcategories_delete', 'description' => 'Deletar'),
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Gerencimento',
                'items' => [
                    [
                        'title' => 'Usuários',
                        'items' => [
                            array('name' => 'users_view', 'description' => 'Visualizar'),
                            array('name' => 'users_create', 'description' => 'Criar'),
                            array('name' => 'users_edit', 'description' => 'Editar'),
                            array('name' => 'users_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Atribuições',
                        'items' => [
                            array('name' => 'roles_view', 'description' => 'Visualizar'),
                            array('name' => 'roles_create', 'description' => 'Criar'),
                            array('name' => 'roles_edit', 'description' => 'Editar'),
                            array('name' => 'roles_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Permissões',
                        'items' => [
                            array('name' => 'permissions_view', 'description' => 'Visualizar'),
                            array('name' => 'permissions_create', 'description' => 'Criar'),
                            array('name' => 'permissions_edit', 'description' => 'Editar'),
                            array('name' => 'permissions_delete', 'description' => 'Deletar'),
                        ]
                    ]
                ]
            ],
            [
                'title' => 'Configurações',
                'items' => [
                    [
                        'title' => 'Parâmetros',
                        'items' => [
                            array('name' => 'parameters_view', 'description' => 'Visualizar'),
                            array('name' => 'parameters_create', 'description' => 'Criar'),
                            array('name' => 'parameters_edit', 'description' => 'Editar'),
                            array('name' => 'parameters_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Faq',
                        'items' => [
                            array('name' => 'faqs_view', 'description' => 'Visualizar'),
                            array('name' => 'faqs_create', 'description' => 'Criar'),
                            array('name' => 'faqs_edit', 'description' => 'Editar'),
                            array('name' => 'faqs_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Mídias',
                        'items' => [
                            array('name' => 'banners_view', 'description' => 'Visualizar'),
                            array('name' => 'banners_create', 'description' => 'Criar'),
                            array('name' => 'banners_edit', 'description' => 'Editar'),
                            array('name' => 'banners_delete', 'description' => 'Deletar'),
                        ]
                    ],
                    [
                        'title' => 'Configurações',
                        'items' => [
                            array('name' => 'settings_edit', 'description' => 'Editar'),
                        ]
                    ],
                ]
            ]
        ];
    }
}
