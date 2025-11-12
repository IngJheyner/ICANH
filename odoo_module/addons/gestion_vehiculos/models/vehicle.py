# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import ValidationError


class Vehicle(models.Model):
    """
    Modelo para gestionar vehículos.
    
    Equivalente al modelo Vehicle de Laravel.
    
    Campos:
    - model: Modelo del vehículo
    - vehicle_brand_id: Relación Many2one con VehicleBrand
    - number_of_doors: Número de puertas
    - color: Color del vehículo
    - owner_ids: Relación Many2many con Person
    """
    
    _name = 'gestion_vehiculos.vehicle'
    _description = 'Vehículo'
    _order = 'model'
    _rec_name = 'model'
    
    # ============================================
    # CAMPOS
    # ============================================
    
    model = fields.Char(
        string='Modelo',
        required=True,
        index=True,
        help='Modelo del vehículo (ej: Corolla, Civic, etc.)'
    )
    
    # Relación Many2one con VehicleBrand (belongsTo en Laravel)
    # Un Vehicle pertenece a una VehicleBrand
    vehicle_brand_id = fields.Many2one(
        comodel_name='gestion_vehiculos.vehicle_brand',
        string='Marca',
        required=True,
        ondelete='cascade',  # Si se elimina la marca, se eliminan sus vehículos
        index=True,
        help='Marca del vehículo'
    )
    
    number_of_doors = fields.Integer(
        string='Número de Puertas',
        required=True,
        default=4,
        help='Cantidad de puertas del vehículo'
    )
    
    color = fields.Char(
        string='Color',
        required=True,
        help='Color del vehículo'
    )
    
    # Relación Many2many con Person (belongsToMany en Laravel)
    # Un Vehicle puede tener muchos Propietarios (Person)
    # Una Person puede tener muchos Vehicles
    owner_ids = fields.Many2many(
        comodel_name='gestion_vehiculos.person',
        relation='gestion_vehiculos_person_vehicle_rel',  # Misma tabla pivote que en Person
        column1='vehicle_id',
        column2='person_id',
        string='Propietarios',
        help='Personas propietarias de este vehículo'
    )
    
    # Campos calculados
    owner_count = fields.Integer(
        string='Cantidad de Propietarios',
        compute='_compute_owner_count',
        store=True,
        help='Número de propietarios de este vehículo'
    )
    
    full_name = fields.Char(
        string='Nombre Completo',
        compute='_compute_full_name',
        store=True,
        help='Marca + Modelo del vehículo'
    )
    
    # ============================================
    # MÉTODOS COMPUTADOS
    # ============================================
    
    @api.depends('owner_ids')
    def _compute_owner_count(self):
        """Calcula la cantidad de propietarios"""
        for record in self:
            record.owner_count = len(record.owner_ids)
    
    @api.depends('model', 'vehicle_brand_id.brand_name')
    def _compute_full_name(self):
        """Genera el nombre completo del vehículo (Marca Modelo)"""
        for record in self:
            if record.vehicle_brand_id and record.model:
                record.full_name = f"{record.vehicle_brand_id.brand_name} {record.model}"
            else:
                record.full_name = record.model or 'Sin nombre'
    
    # ============================================
    # VALIDACIONES
    # ============================================
    
    @api.constrains('model')
    def _check_model_not_empty(self):
        """Valida que model no esté vacío"""
        for record in self:
            if record.model and not record.model.strip():
                raise ValidationError('El modelo no puede estar vacío.')
    
    @api.constrains('color')
    def _check_color_not_empty(self):
        """Valida que color no esté vacío"""
        for record in self:
            if record.color and not record.color.strip():
                raise ValidationError('El color no puede estar vacío.')
    
    @api.constrains('number_of_doors')
    def _check_number_of_doors(self):
        """Valida que number_of_doors sea válido"""
        for record in self:
            if record.number_of_doors < 2 or record.number_of_doors > 6:
                raise ValidationError('El número de puertas debe estar entre 2 y 6.')
    
    @api.constrains('vehicle_brand_id')
    def _check_vehicle_brand_exists(self):
        """Valida que la marca exista"""
        for record in self:
            if not record.vehicle_brand_id:
                raise ValidationError('Debe seleccionar una marca de vehículo.')
    
    # ============================================
    # MÉTODOS DE NEGOCIO
    # ============================================
    
    def name_get(self):
        """Personaliza la representación del nombre"""
        result = []
        for record in self:
            name = f"{record.vehicle_brand_id.brand_name} {record.model} ({record.color})"
            result.append((record.id, name))
        return result
    
    @api.model
    def create(self, vals):
        """Hook al crear"""
        # Normalizar campos
        if 'model' in vals:
            vals['model'] = vals['model'].strip()
        if 'color' in vals:
            vals['color'] = vals['color'].strip()
        
        return super(Vehicle, self).create(vals)
    
    def write(self, vals):
        """Hook al actualizar"""
        # Normalizar campos
        if 'model' in vals:
            vals['model'] = vals['model'].strip()
        if 'color' in vals:
            vals['color'] = vals['color'].strip()
        
        return super(Vehicle, self).write(vals)
    
    def action_add_owner(self):
        """
        Acción para agregar un propietario al vehículo.
        Se usa en la interfaz con un wizard.
        """
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': 'Agregar Propietario',
            'res_model': 'gestion_vehiculos.person',
            'view_mode': 'list,form',
            'domain': [('id', 'not in', self.owner_ids.ids)],
            'target': 'new',
        }
    
    def action_view_owners(self):
        """
        Acción para ver los propietarios del vehículo.
        """
        self.ensure_one()
        return {
            'type': 'ir.actions.act_window',
            'name': f'Propietarios de {self.full_name}',
            'res_model': 'gestion_vehiculos.person',
            'view_mode': 'list,form',
            'domain': [('id', 'in', self.owner_ids.ids)],
        }

