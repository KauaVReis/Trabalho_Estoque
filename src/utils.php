<?php
/**
 * Formata um CNPJ ou CPF.
 * @param string $valor O valor numérico (pode ter caracteres não numéricos que serão removidos)
 * @return string O valor formatado ou o original se não tiver o tamanho esperado
 */
function formatarDocumento($valor) {
    $valor = preg_replace('/\D/', '', $valor);
    
    if (strlen($valor) === 14) {
        // CNPJ: 00.000.000/0000-00
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $valor);
    } elseif (strlen($valor) === 11) {
        // CPF: 000.000.000-00
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $valor);
    }
    
    return $valor;
}

/**
 * Formata um telefone.
 * @param string $valor O valor numérico
 * @return string O valor formatado
 */
function formatarTelefone($valor) {
    $valor = preg_replace('/\D/', '', $valor);
    
    if (strlen($valor) === 11) {
        // Celular: (00) 00000-0000
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $valor);
    } elseif (strlen($valor) === 10) {
        // Fixo: (00) 0000-0000
        return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $valor);
    }
    
    return $valor;
}
?>
