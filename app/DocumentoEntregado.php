<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DocumentoEntregado extends Model
{
    protected $table = 'documentos_entregados';
    protected $fillable = ['gestion_proyecto_id','documento_id','observacion'];

    public function documento()
    {
        return $this->belongsTo(Documento::class, 'documento_id');
    }
    public function gestion_proy()
    {
        return $this->belongsTo(GestionProyecto::class, 'gestion_proyecto_id');
    }
    public function scopeYear($query,$year){
        return $query->where('fecha_registro',$year);
    }
}
