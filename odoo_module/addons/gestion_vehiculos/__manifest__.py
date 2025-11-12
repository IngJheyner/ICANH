# -*- coding: utf-8 -*-
{
    'name': 'Gestión de Vehículos',
    'version': '18.0.1.0.0',
    'category': 'Fleet',
    'summary': 'Módulo para gestionar vehículos, marcas y propietarios',
    'description': """
        Módulo de Gestión de Vehículos
        ================================
        
        Características:
        - Gestión de marcas de vehículos (VehicleBrand)
        - Gestión de personas propietarias (Person)
        - Gestión de vehículos con relaciones Many2one y Many2many
        - API REST para operaciones CRUD
        
        Desarrollado como desafío técnico - Parte 3 (Odoo)
    """,
    'author': 'Jheyner',
    'website': 'https://github.com/IngJheyner/ICANH',
    'license': 'LGPL-3',
    'depends': ['base', 'web'],
    'data': [
        # Seguridad
        'security/ir.model.access.csv',
        # Vistas
        'views/vehicle_brand_views.xml',
        'views/person_views.xml',
        'views/vehicle_views.xml',
        'views/menu.xml',
    ],
    'demo': [],
    'installable': True,
    'application': True,
    'auto_install': False,
}

