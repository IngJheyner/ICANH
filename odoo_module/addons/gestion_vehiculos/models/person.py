# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import ValidationError


class Person(models.Model):
    """
    Modelo para gestionar personas propietarias de vehículos.
    
    Equivalente al modelo Person de Laravel.
    
    Campos:
    - name: Nombre completo de la persona
    - identification_number: Número de identificación único
    - vehicle_ids: Relación Many2many con vehículos
    """
    
    _name = 'gestion_vehiculos.person'
    _description = 'Persona'
    _order = 'name'
    _rec_name = 'name'
    
    # ============================================
    # CAMPOS
    # ============================================
    
    name = fields.Char(
        string='Nombre Completo',
        required=True,
        index=True,
        help='Nombre completo de la persona'
    )
    
    identification_number = fields.Char(
        string='Número de Identificación',
        required=True,
        index=True,
        help='Número único de identificación (DNI, CC, etc.)'
    )
    
    # Relación Many2many con Vehicles (belongsToMany en Laravel)
    # Una Persona puede tener muchos Vehículos
    # Un Vehículo puede tener muchos Propietarios
    vehicle_ids = fields.Many2many(
        comodel_name='gestion_vehiculos.vehicle',
        relation='gestion_vehiculos_person_vehicle_rel',  # Nombre de la tabla pivote
        column1='person_id',                              # Columna de este modelo
        column2='vehicle_id',                             # Columna del otro modelo
        string='Vehículos',
        help='Vehículos asociados a esta persona'
    )
    
    # Campo calculado: cantidad de vehículos
    vehicle_count = fields.Integer(
        string='Cantidad de Vehículos',
        compute='_compute_vehicle_count',
        store=True,
        help='Número total de vehículos que posee esta persona'
    )
    
    # ============================================
    # CONSTRAINTS
    # ============================================
    
    _sql_constraints = [
        ('identification_number_unique', 'UNIQUE(identification_number)', 
         'El número de identificación ya existe. Debe ser único.'),
    ]
    
    # ============================================
    # MÉTODOS COMPUTADOS (como Accessors en Laravel)
    # ============================================
    
    @api.depends('vehicle_ids')
    def _compute_vehicle_count(self):
        """Calcula la cantidad de vehículos de la persona"""
        for record in self:
            record.vehicle_count = len(record.vehicle_ids)
    
    # ============================================
    # VALIDACIONES
    # ============================================
    
    @api.constrains('name')
    def _check_name_not_empty(self):
        """Valida que name no esté vacío"""
        for record in self:
            if record.name and not record.name.strip():
                raise ValidationError('El nombre no puede estar vacío.')
    
    @api.constrains('identification_number')
    def _check_identification_number(self):
        """Valida que identification_number no esté vacío y tenga formato válido"""
        for record in self:
            if record.identification_number:
                cleaned = record.identification_number.strip()
                if not cleaned:
                    raise ValidationError('El número de identificación no puede estar vacío.')
                if len(cleaned) < 3:
                    raise ValidationError('El número de identificación debe tener al menos 3 caracteres.')
    
    # ============================================
    # MÉTODOS DE NEGOCIO
    # ============================================
    
    def name_get(self):
        """Personaliza la representación del nombre"""
        result = []
        for record in self:
            name = f"{record.name} (ID: {record.identification_number})"
            result.append((record.id, name))
        return result
    
    @api.model
    def create(self, vals):
        """Hook al crear"""
        # Normalizar campos
        if 'name' in vals:
            vals['name'] = vals['name'].strip()
        if 'identification_number' in vals:
            vals['identification_number'] = vals['identification_number'].strip()
        
        return super(Person, self).create(vals)
    
    def write(self, vals):
        """Hook al actualizar"""
        # Normalizar campos
        if 'name' in vals:
            vals['name'] = vals['name'].strip()
        if 'identification_number' in vals:
            vals['identification_number'] = vals['identification_number'].strip()
        
        return super(Person, self).write(vals)
    
    def action_view_vehicles(self):
        """
        Método de acción para abrir la vista de vehículos de esta persona.
        Esto se usará en la interfaz para navegar a los vehículos.
        """
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Vehículos de {self.name}',
            'res_model': 'gestion_vehiculos.vehicle',
            'view_mode': 'list,form',
            'domain': [('owner_ids', 'in', self.ids)],
            'context': {'default_owner_ids': [(4, self.id)]},
        }

