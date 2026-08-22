<?php

namespace Controllers;

use Model\OrdenCompra;

class APIOrdenCompraRecepcion {

    public static function listarPorRecibir() {

        if(is_auth()) {

            $valor = [
                $_SESSION['idempresa'],
                $_SESSION['idtienda']
            ];

            $ordenes = OrdenCompra::procedureLista(
                'prc_compra_listar_por_recibir',
                $valor
            ) ?? [];

            echo json_encode(
                $ordenes,
                JSON_UNESCAPED_SLASHES
            );
        }
    }
}