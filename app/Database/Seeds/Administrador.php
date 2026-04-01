<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Administrador extends Seeder
{
    public function run()
    {
        //Insertamos nombre del sistema
        $data = [
            'code' => '12345678',
            'name'    => 'Franz Condori',
            'email'    => 'sysfranzco@gmail.com',
            'password'    => '550e1bafe077ff0b0b67f4e32f29d751',
            'level'    => '1',
            'employee_id'    => '100',
        ];
        $this->db->table('admin')->insert($data);
    }
}
