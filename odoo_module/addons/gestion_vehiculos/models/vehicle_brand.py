# -*- coding: utf-8 -*-

from odoo import models, fields, api
from odoo.exceptions import ValidationError


class VehicleBrand(models.Model):
    """
    Modelo para gestionar marcas de vehículos.
    
    Equivalente al modelo VehicleBrand de Laravel.
    
    Campos:
    - brand_name: Nombre único de la marca
    - country: País de origen de la marca
    - vehicle_ids: Relación One2many con vehículos
    """
    
    # Nombre técnico de la tabla en la BD (se crea automáticamente como: gestion_vehiculos_vehicle_brand)
    _name = 'gestion_vehiculos.vehicle_brand'
    
    # Nombre legible para mostrar en la UI
    _description = 'Marca de Vehículo'
    
    # Campo por el cual se ordena por defecto
    _order = 'brand_name'
    
    # Campo que se usa como "nombre" del registro (para referencias)
    _rec_name = 'brand_name'
    
    # ============================================
    # CAMPOS (equivalente a fillable en Laravel)
    # ============================================
    
    brand_name = fields.Char(
        string='Nombre de la Marca',
        required=True,
        index=True,
        help='Nombre único de la marca de vehículo'
    )
    
    country = fields.Char(
        string='País',
        required=True,
        help='País de origen de la marca'
    )
    
    # Relación One2many (hasMany en Laravel)
    # Un VehicleBrand tiene muchos Vehicles
    vehicle_ids = fields.One2many(
        comodel_name='gestion_vehiculos.vehicle',  # Modelo relacionado
        inverse_name='vehicle_brand_id',            # Campo en el otro modelo que apunta a este
        string='Vehículos',
        help='Lista de vehículos de esta marca'
    )
    
    # Campos automáticos (como timestamps en Laravel)
    # create_uid, create_date, write_uid, write_date se crean automáticamente
    
    # ============================================
    # CONSTRAINTS (validaciones a nivel de BD)
    # ============================================
    
    _sql_constraints = [
        ('brand_name_unique', 'UNIQUE(brand_name)', 
         'El nombre de la marca ya existe. Debe ser único.'),
    ]
    
    # ============================================
    # MÉTODOS DE VALIDACIÓN (como Rules en Laravel)
    # ============================================
    
    @api.constrains('brand_name')
    def _check_brand_name_not_empty(self):
        """Valida que brand_name no esté vacío"""
        for record in self:
            if record.brand_name and not record.brand_name.strip():
                raise ValidationError('El nombre de la marca no puede estar vacío.')
    
    @api.constrains('country')
    def _check_country_not_empty(self):
        """Valida que country no esté vacío"""
        for record in self:
            if record.country and not record.country.strip():
                raise ValidationError('El país no puede estar vacío.')
    
    # ============================================
    # MÉTODOS DE NEGOCIO
    # ============================================
    
    def name_get(self):
        """
        Personaliza cómo se muestra el nombre del registro.
        Similar a un accessor en Laravel.
        """
        result = []
        for record in self:
            name = f"{record.brand_name} ({record.country})"
            result.append((record.id, name))
        return result
    
    @api.model
    def create(self, vals):
        """
        Hook llamado al crear un registro.
        Similar a un Observer o Event en Laravel.
        """
        # Normalizar brand_name (eliminar espacios extra)
        if 'brand_name' in vals:
            vals['brand_name'] = vals['brand_name'].strip()
        if 'country' in vals:
            vals['country'] = vals['country'].strip()
        
        return super(VehicleBrand, self).create(vals)
    
    def write(self, vals):
        """
        Hook llamado al actualizar un registro.
        Similar a un Observer o Event en Laravel.
        """
        # Normalizar campos antes de actualizar
        if 'brand_name' in vals:
            vals['brand_name'] = vals['brand_name'].strip()
        if 'country' in vals:
            vals['country'] = vals['country'].strip()
        
        return super(VehicleBrand, self).write(vals)

