<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Setting extends Seeder
{
    public function run()
    {
        //Insertamos nombre del sistema
        $data = [
            'type' => 'system_name',
            'description'    => 'saat2023',
        ];
        $this->db->table('settings')->insert($data);

        //Insertamos Titulo del Sistema
        $data = [
            'type' => 'system_title',
            'description'    => 'SAAT - Tiquipaya',
        ];
        $this->db->table('settings')->insert($data);
    }
}
