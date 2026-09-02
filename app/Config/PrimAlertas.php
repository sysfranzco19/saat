<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class PrimAlertas extends BaseConfig
{
    /**
     * Mientras esté en false (versión de prueba), el correo automático de
     * alerta de cupo trimestral (6 días, Primaria 3ro-6to) NO se envía de
     * verdad — la alerta igual queda registrada en prim_alertas_cupo y
     * visible en manager/alertas_cupo, solo se omite el mail().
     * Cambiar a true cuando el sistema pase a producción.
     */
    public bool $enviarCorreoAlerta6 = false;
}
