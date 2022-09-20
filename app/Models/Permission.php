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
                        ]
                    ],
                    [
                        'title' => 'Operacionais',
                        'items' => [
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
                        ]
                    ],
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
