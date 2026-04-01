<?php

namespace App\Models;

use CodeIgniter\Model;

class PeriodoModel extends Model
{
    protected $DBGroup = 'asistencia';
    protected $table = 'periodo';
    protected $primaryKey = 'periodo_id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['periodo', 'hora_inicio', 'hora_fin', 'nivel_id'];

    // Listar todos los periodos con el nombre del nivel
    public function listar_periodos()
    {
        return $this->select('periodo.*, nivel.nivel as nombre_nivel')
            ->join('nivel', 'nivel.id = periodo.nivel_id')
            ->findAll();
    }
    public function listar_periodos_section($section_id)
    {
        return $this->select('periodo.*, nivel.nivel as nombre_nivel')
            ->join('nivel', 'nivel.id = periodo.nivel_id')
            ->where('nivel.inicio <=', $section_id)
            ->where('nivel.fin >=', $section_id)
            ->findAll();
    }

    // Obtener un periodo por ID
    public function get_periodo($data)
    {
        return $this->where($data)->findAll();
    }

    // Insertar un nuevo periodo
    public function insert_periodo($data)
    {
        return $this->insert($data);
    }

    // Actualizar un periodo
    public function update_periodo($data, $id)
    {
        return $this->update($id, $data);
    }

    // Eliminar un periodo
    public function delete_periodo($id)
    {
        return $this->delete($id);
    }
}
