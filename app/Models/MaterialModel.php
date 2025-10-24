<?php

namespace App\Models;

use CodeIgniter\Model;

class MaterialModel extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'course_id',
        'file_name',
        'file_path',
        'created_at',
        'updated_at',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Insert a new material record
     */
    public function insertMaterial($data)
    {
        return $this->insert($data);
    }

    /**
     * Get all materials for a specific course
     */
    public function getMaterialsByCourse($course_id)
    {
        return $this->where('course_id', $course_id)
                    ->orderBy('created_at', 'DESC')
                    ->findAll();
    }

    /**
     * Get material by ID
     */
    public function getMaterialById($material_id)
    {
        return $this->find($material_id);
    }

    /**
     * Delete material by ID
     */
    public function deleteMaterial($material_id)
    {
        return $this->delete($material_id);
    }

    /**
     * Get materials with course information
     */
    public function getMaterialsWithCourse($course_id = null)
    {
        $builder = $this->db->table($this->table . ' m');
        $builder->select('m.*, c.course as course_name');
        $builder->join('courses c', 'c.id = m.course_id');

        if ($course_id) {
            $builder->where('m.course_id', $course_id);
        }

        return $builder->get()->getResultArray();
    }
}
