<?php

if (!function_exists('isPrimaria36')) {
    /**
     * Returns true if the grade string corresponds to 3ro-6to Primaria.
     * Inicial, 1ro Primaria, 2do Primaria, and all Secundaria return false.
     */
    function isPrimaria36(string $grade): bool
    {
        if (stripos($grade, 'primaria') === false) return false;
        return (bool) preg_match('/\b(3ro|3°|tercero|4to|4°|cuarto|5to|5°|quinto|6to|6°|sexto)\b/i', $grade);
    }
}
