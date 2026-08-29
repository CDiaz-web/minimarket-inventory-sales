<?php

namespace Controllers;

use Model\OrdenCompra;
use Model\OrdenCompraDetalle;

class APIObtenerOrdenCompra {

    public static function obtenerRecepcion() {

        if (is_auth()) {

            $idOrden = isset($_GET['id'])
                ? (int) $_GET['id']
                : 0;

            if (!$idOrden) {
                echo json_encode([
                    'error' => 'Orden de compra no válida'
                ]);
                return;
            }

            $idEmpresa = $_SESSION['idempresa'];
            $idTienda  = $_SESSION['idtienda'];

            $cabecera = OrdenCompra::procedureLista(
                'prc_compra_obtener',
                [1, $idEmpresa, $idTienda, $idOrden]
            );

            $detalle = OrdenCompraDetalle::procedureLista(
                'prc_compra_obtener',
                [2, $idEmpresa, $idTienda, $idOrden]
            );

            echo json_encode([
                'cabecera' => $cabecera[0] ?? null,
                'detalle'  => $detalle ?? []
            ], JSON_UNESCAPED_UNICODE);
        }
    }
}