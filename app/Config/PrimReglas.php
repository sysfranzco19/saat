<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class PrimReglas extends BaseConfig
{
    /**
     * Hora límite (formato H:i) para que un padre/madre pueda solicitar hoy
     * mismo, por la plataforma: una licencia por día(s) que empiece hoy
     * (Parents::license_save_dia), o un cambio de recojo para hoy
     * (Parents::prim_cambio_recojo_create). Después de esta hora, ambas
     * solicitudes se bloquean del lado de padres — secretaría/Dirección
     * Técnica pueden seguir registrándolas manualmente sin este límite.
     */
    public string $horaCierre = '10:00';
}
