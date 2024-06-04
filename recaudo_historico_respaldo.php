<?php 
ini_set('display_errors', 1);
error_reporting(E_ALL);
include 'menu.php';


		$OK='';
if (isset($_POST['buscar'])) {
    if ($_POST['documento_tipo'] == "" && ($_POST['tiporecaudo'] <> 3 && $_POST['tiporecaudo'] != 4)) {
        echo "<script>alert(\"El numero de documento no puede estar vacio.\");</script>";
    } else {
        if ($_POST['tiporecaudo'] == 1) {
            $query_ap = "SELECT TAcuerdop_comparendo, TAcuerdop_numero FROM acuerdos_pagos WHERE TAcuerdop_numero='" . trim($_POST['documento_tipo']) . "' OR TAcuerdop_identificacion= '" . trim($_POST['documento_tipo']) . "' GROUP BY TAcuerdop_comparendo, TAcuerdop_numero ORDER BY TAcuerdop_numero";
            $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
            
            $result_ap= sqlsrv_num_rows($result_ap);
            if ($result_ap==0){echo "<script>alert(\"El Acuerdo de Pago no existe o la identificacion no tiene AP's.\");</script>";}
        } elseif ($_POST['tiporecaudo'] == 2) {
            $query_comp = "SELECT Tcomparendos_comparendo FROM comparendos WHERE (Tcomparendos_comparendo='".$_POST['documento_tipo']."' or Tcomparendos_idinfractor=".$_POST['documento_tipo'].") AND Tcomparendos_estado NOT IN (2, 3, 4)";
            $result_comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered'));
            $row_comp = $result_comp->fetch_array(MYSQLI_ASSOC);
            $result_comp= sqlsrv_num_rows($result_comp);
            if ($result_comp==0){echo "<script>alert(\"                  NO HUBO RESULTADOS \\n\\nPosibles razones: \\n1. El Comparendo no existe. \\n2. o esta en estado Recaudado. \\n3. o esta en acuerdo de pago. \\n4. o esta en preacuerdo \\n5. o el ciudadano no tiene comparendos.\");</script>";}
        }
    }
}

			
					
		if (@$_POST['guardar']){
						
		    if ($_POST['tiporecaudo'] == 1) {
    $query_ap = "SELECT TAcuerdop_comparendo, TAcuerdop_periodicidad, TAcuerdop_identificacion, TAcuerdop_cuota, TAcuerdop_valor, TAcuerdop_fechapago, TAcuerdop_estado FROM acuerdos_pagos WHERE TAcuerdop_numero='" . $_POST['documento_tipo'] . "'";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    $query_rec = "BEGIN TRANSACTION;";
    $inserciones = 0;
    $subtotal = 0;

    while ($row_linea = sqlsrv_fetch_array($result_ap, SQLSRV_FETCH_ASSOC)) {
        if ($_POST['recaudar_cuota_' . $row_linea['TAcuerdop_cuota']] == 1) {
            if ($_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] == "" || $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] == "") {
                echo "<script>alert(\"El valor de la cuota, Fecha recaudo o Liquidacion no pueden estar vacios.\");</script>";
                echo "<script>self.location=\"recaudo_historico.php\";</script>";
            } else {
                $hoy = date("Y-m-d H:i:s");
                $query_rec = $query_rec . "INSERT INTO recaudos (liquidacion, fecha, valor, identificacion_pagador, fechayhora, usuario, forma_pago) VALUES ('" . $_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "', " . $_POST['valor_' . $row_linea['TAcuerdop_cuota']] . ", '" . trim($row_linea['TAcuerdop_identificacion']) . "', '" . $hoy . "', '" . $idusuario . "', 1);\n";
                $query_rec = $query_rec . "UPDATE acuerdos_pagos SET TAcuerdop_estado=2, TAcuerdop_valor=" . $_POST['valor_' . $row_linea['TAcuerdop_cuota']] . " WHERE TAcuerdop_numero='" . $_POST['documento_tipo'] . "' AND TAcuerdop_cuota =" . $row_linea['TAcuerdop_cuota'] . " AND TAcuerdop_identificacion=" . $row_linea['TAcuerdop_identificacion'] . ";\n";
                $query_rec = $query_rec . "SET IDENTITY_INSERT liquidaciones ON;\n ";
                $query_rec = $query_rec . "INSERT INTO liquidaciones (id, fecha, ciudadano, placa, estado, Tliquidacionmain_caduca, Tliquidacionmain_subtotal, Tliquidacionmain_tipodoc, Tliquidacionmain_user, Tliquidacionmain_fecharecaudo) VALUES (" . $_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] . ", '" . $hoy . "', '" . trim($row_linea['TAcuerdop_identificacion']) . "', '" . trim($_POST['placa']) . "', 3, '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "', " . $_POST['valor_' . $row_linea['TAcuerdop_cuota']] . ",  6, '" . $_SESSION['MM_Username'] . "', '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "');\n";
                $query_rec = $query_rec . "INSERT INTO Tliquidaciontramites (Tliquidaciontramites_liq, Tliquidaciontramites_tramite, Tliquidaciontramites_valor, Tliquidaciontramites_estado, Tliquidaciontramites_user, Tliquidaciontramites_fecha) VALUES (" . $_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] . ", 40, " . $_POST['valor_' . $row_linea['TAcuerdop_cuota']] . ", 3, '" . $_SESSION['MM_Username'] . "', '" . $hoy . "');\n";
                $query_rec = $query_rec . "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_doc, Tliqconcept_terceros) VALUES ('" . traenombrecampo(TAcuerdop, TAcuerdop_numero, TAcuerdop_id, TAcuerdop_numero, "'" . trim($_POST['documento_tipo']) . "' AND TAcuerdop_cuota=" . $row_linea['TAcuerdop_cuota']) . "', 6, " . $_POST['valor_' . $row_linea['TAcuerdop_cuota']] . ", 1, '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "', 40, '" . $_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_SESSION['MM_Username'] . "', '" . traenombrecampo(TAcuerdop, TAcuerdop_numero, TAcuerdop_id, TAcuerdop_numero, "'" . trim($_POST['documento_tipo']) . "' AND TAcuerdop_cuota=" . $row_linea['TAcuerdop_cuota']) . "', 0);\n";

                if ($_POST[origen] = 99999999) {
                    $array = array(38, 51, 52);
                    foreach ($array as $valor) {
                        $query_rec = $query_rec . "INSERT INTO detalle_conceptos_liquidaciones (concepto, valor, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf) SELECT Tconceptos_nombre, Tconceptos_tipodoc, Tconceptos_valor, Tconceptos_smlv, Tconceptos_fechaini, Tconceptos_fechafin, Tconceptos_terceros, Tconceptos_porcentaje, Tconceptos_operacion, Tconceptos_repetir, 59, '" . $_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_SESSION['MM_Username'] . "', Tconceptos_IPC, '" . traenombrecampo(TAcuerdop, TAcuerdop_numero, TAcuerdop_id, TAcuerdop_numero, "'" . trim($_POST['documento_tipo']) . "' AND TAcuerdop_cuota=" . $row_linea['TAcuerdop_cuota']) . "', Tconceptos_decreto, Tconceptos_infraccion, " . $_POST[origen] . ", Tconceptos_ayudas, Tconceptos_clase, Tconceptos_fechainif, Tconceptos_fechafinf, Tconceptos_ppi, Tconceptos_ppf FROM conceptos WHERE id=" . $valor . ";\n";
                    }
                } elseif ($_POST[origen] = 6756) {
                    $array = array(38, 53);
                    foreach ($array as $valor) {
                        $query_rec = $query_rec . "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf) SELECT Tconceptos_nombre, Tconceptos_tipodoc, Tconceptos_valor, Tconceptos_smlv, Tconceptos_fechaini, Tconceptos_fechafin, Tconceptos_terceros, Tconceptos_porcentaje, Tconceptos_operacion, Tconceptos_repetir, 59, '" . $_POST['liq_no_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_POST['fecha_recaudo_' . $row_linea['TAcuerdop_cuota']] . "', '" . $_SESSION['MM_Username'] . "', Tconceptos_IPC, '" . traenombrecampo(TAcuerdop, TAcuerdop_numero, TAcuerdop_id, TAcuerdop_numero, "'" . trim($_POST['documento_tipo']) . "' AND TAcuerdop_cuota=" . $row_linea['TAcuerdop_cuota']) . "', Tconceptos_decreto, Tconceptos_infraccion, " . $_POST[origen] . ", Tconceptos_ayudas, Tconceptos_clase, Tconceptos_fechainif, Tconceptos_fechafinf, Tconceptos_ppi, Tconceptos_ppf FROM conceptos WHERE Tconceptos_ID=" . $valor . ";\n";
                    }
                }
                $inserciones++;
            }
        }
    }
    $query_rec = $query_rec . "COMMIT;";
    if ($inserciones != 0) {
        $result_ap1=sqlsrv_query( $mysqli,$query_rec, array(), array('Scrollable' => 'buffered'));
        //echo $query_rec;
    }
    if (!$result_ap1) {
        echo "<script>alert(\"La actualizacion no se pudo realizar, revise los valores!!!\");</script>";
        echo "<script>self.location=\"recaudo_historico.php\";</script>";
    } else {
        echo "<script>alert(\"La actualizacion se ejecuto exitosamente.\");</script>";
    }



		    
		} elseif ($_POST['tiporecaudo'] == 2) { // Comparendo
    if ($_POST['1000000022'] == "" || $_POST['fecha'] == "" || $_POST['liquidacion'] == "") {
        echo "<script>alert(\"El numero de la liquidacion y Fecha de recaudo no pueden estar vacios.\");</script>";
        echo "<script>self.location='recaudo_historico.php';</script>";
    } else {
        $hoy = date("Y-m-d H:i:s"); /* Cambiado 2018-03-21 */
        $query_rec = "BEGIN TRANSACTION;";
        $query_rec = $query_rec . "UPDATE comparendos SET Tcomparendos_estado=2  WHERE Tcomparendos_comparendo=" . $_POST['documento_tipo'] . " AND Tcomparendos_idinfractor=" . $_POST['infractor'] . ";\n";

        $query_rec = $query_rec . "SET IDENTITY_INSERT Tliquidacionmain ON;\n ";
        $query_rec = $query_rec . "INSERT INTO Tliquidacionmain (Tliquidacionmain_ID, Tliquidacionmain_fecha, Tliquidacionmain_idciudadano, Tliquidacionmain_idtramitador, Tliquidacionmain_placa, Tliquidacionmain_estado, Tliquidacionmain_caduca, Tliquidacionmain_subtotal, Tliquidacionmain_tipodoc, Tliquidacionmain_user, Tliquidacionmain_fecharecaudo) VALUES
            (" . $_POST['liquidacion'] . ", '" . $hoy . "', '" . $_POST['infractor'] . "', '" . $_POST['infractor'] . "', '" . trim($_POST['placa']) . "'" . ", 3, '" . $_POST['fecha'] . "', " . $_POST['total'] . ",  4, '" . $_SESSION['MM_Username'] . "', '" . $_POST['fecha'] . "');\n";

        $query_rec = $query_rec . "INSERT INTO Tliquidaciontramites (Tliquidaciontramites_liq, Tliquidaciontramites_tramite, Tliquidaciontramites_valor, Tliquidaciontramites_estado, Tliquidaciontramites_user, Tliquidaciontramites_fecha) VALUES
            (" . $_POST['liquidacion'] . ", 39, " . $_POST['total'] . ", 3, '" . $_SESSION['MM_Username'] . "', '" . $hoy . "');\n";

        //1. Nombre Tabla 2. Campo de busqueda (where), 3. campo select, 4. Campo de order, 5. condicion
        $comparendo = traenombrecampo(Tcomparendos, Tcomparendos_comparendo, Tcomparendos_ID, Tcomparendos_ID, $_POST['documento_tipo']);

        $query_rec = $query_rec . "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_doc, Tliqconcept_terceros) VALUES
            ('" . $comparendo . "', 4, " . $_POST['total'] . ", 0, '" . $_POST['fecha'] . "', 39, '" . $_POST['liquidacion'] . "', '" . $_POST['fecha'] . "', '" . $_SESSION['MM_Username'] . "', '" . $comparendo . "', 0);\n";

        $array = array(1000000050, 134, 54, 1000000051, 1000000020, 1000000016, 1000000021, 38);
        foreach ($array as $valor) {
            if ((int)$_POST[$valor] > 0 || $valor == 38) {
                $query_rec = $query_rec . "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf)
                SELECT Tconceptos_nombre, Tconceptos_tipodoc, Tconceptos_valor, Tconceptos_smlv, Tconceptos_fechaini, Tconceptos_fechafin, Tconceptos_terceros, Tconceptos_porcentaje, Tconceptos_operacion, Tconceptos_repetir, 59, '" . $_POST[liquidacion] . "', '" . $_POST[fecha] . "', '" . $_SESSION['MM_Username'] . "', Tconceptos_IPC, '" . $comparendo . "', Tconceptos_decreto, Tconceptos_infraccion, " . $_POST[origen] . ", Tconceptos_ayudas, Tconceptos_clase, Tconceptos_fechainif, Tconceptos_fechafinf, Tconceptos_ppi, Tconceptos_ppf  FROM conceptos
                WHERE Tconceptos_ID=" . $valor . ";\n";
            }
        }

        if ($_POST[origen] = 99999999) // Insertamos los conceptos polca
        {
            $array = array(51, 52);
            foreach ($array as $valor) {
                $query_rec = $query_rec . "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf)
                SELECT Tconceptos_nombre, Tconceptos_tipodoc, Tconceptos_valor, Tconceptos_smlv, Tconceptos_fechaini, Tconceptos_fechafin, Tconceptos_terceros, Tconceptos_porcentaje, Tconceptos_operacion, Tconceptos_repetir, 59, '" . $_POST[liquidacion] . "', '" . $_POST[fecha] . "', '" . $_SESSION['MM_Username'] . "', Tconceptos_IPC, '" . $comparendo . "', Tconceptos_decreto, Tconceptos_infraccion, " . $_POST[origen] . ", Tconceptos_ayudas, Tconceptos_clase, Tconceptos_fechainif, Tconceptos_fechafinf, Tconceptos_ppi, Tconceptos_ppf  FROM conceptos
                WHERE Tconceptos_ID=" . $valor . ";\n";
            }
        } elseif ($_POST[origen] = 6756) // Grupo operativo local
        {
            $query_rec = $query_rec . "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_doc, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_origen, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf)
                SELECT Tconceptos_nombre, Tconceptos_tipodoc, Tconceptos_valor, Tconceptos_smlv, Tconceptos_fechaini, Tconceptos_fechafin, Tconceptos_terceros, Tconceptos_porcentaje, Tconceptos_operacion, Tconceptos_repetir, 59, '" . $_POST[liquidacion] . "', '" . $_POST[fecha] . "', '" . $_SESSION['MM_Username'] . "', Tconceptos_IPC, '" . $comparendo . "', Tconceptos_decreto, Tconceptos_infraccion, " . $_POST[origen] . ", Tconceptos_ayudas, Tconceptos_clase, Tconceptos_fechainif, Tconceptos_fechafinf, Tconceptos_ppi, Tconceptos_ppf  FROM conceptos
                WHERE Tconceptos_ID=53;\n";
        }

        $query_rec = $query_rec . "INSERT INTO Trecaudos (Trecaudos_liquidacion, Trecaudos_fecharecaudo, Trecaudos_valor, Trecaudos_identconsig, Trecaudos_nombreconsig, Trecaudos_fecha, Trecaudos_user) VALUES
            (" . $_POST['liquidacion'] . ", '" . $_POST['fecha'] . "', " . $_POST['total'] . ", '" . $_POST['infractor'] . "', '" . traenombrecampo(Tciudadanos, Tciudadanos_ident, "Tciudadanos_nombres+' '+Tciudadanos_apellidos", Tciudadanos_nombres, $_POST['infractor']) . "', '" . $hoy . "', '" . $_SESSION['MM_Username'] . "');\n";
        $query_rec = $query_rec . "COMMIT TRANSACTION;";

		$stmt = sqlsrv_query( $mysqli,$query_rec, array(), array('Scrollable' => 'buffered'));

        if ($stmt) {
            echo "<script>alert(\"La actualizacion se ejecutó exitosamente.\");</script>";
        } else {
            echo "<script>alert(\"La actualización no se pudo realizar, revise los valores!!!\");</script>";
            echo "<script>self.location='recaudo_historico.php';</script>";
        }
    }
}elseif ($_POST['tiporecaudo'] == 3 || $_POST['tiporecaudo'] == 4) { // RNA o RNC
    if ($_POST['tiporecaudo'] == 3) {
        $tipodoc = 1;
    } elseif ($_POST['tiporecaudo'] == 4) {
        $tipodoc = 2;
    }
    $subtotal = 0;
    $query_rna = "SELECT Ttramites_ID, Ttramites_nombre FROM tramites WHERE Ttramites_tipodoc=" . $tipodoc . " ORDER BY Ttramites_nombre";
    $result_rna=sqlsrv_query( $mysqli,$query_rna, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    $query_insert_rna = "BEGIN TRANSACTION InsertRNA;";
    while ($row_rna = mysqli_fetch_array($result_rna)) {
        $tramite = 0;
        $valor_rnc = 0;
        $query_rnc = "SELECT Tconceptos_ID, Tconceptos_nombre, Tconceptos_tipodoc FROM Tconceptos WHERE Tconceptos_ID IN (SELECT Ttramites_conceptos_C FROM Ttramites_conceptos WHERE Ttramites_conceptos_T=" . $row_rna[0] . ")";
        $result_rnc=sqlsrv_query( $mysqli,$query_rnc, array(), array('Scrollable' => 'buffered'))  or die(guardar_error(__LINE__));
        while ($row_rnc = mysqli_fetch_array($result_rnc)) {
            if ($_POST[$row_rna[0] . "_" . $row_rnc[0]] > 0) {
                if ($tramite == 0) {
                    $query_insert_rna .= "INSERT INTO Tliquidaciontramites (Tliquidaciontramites_liq, Tliquidaciontramites_tramite, Tliquidaciontramites_valor, Tliquidaciontramites_estado, Tliquidaciontramites_nc, Tliquidaciontramites_user, Tliquidaciontramites_fecha) VALUES (" . $_POST['liquidacion'] . ", " . $row_rna[0] . ", 0,  3, '', '" . $_SESSION['MM_Username'] . "', '" . $_POST['fecha'] . "');\n";
                    $tramite = 1;
                }
                $query_insert_rna .= "INSERT INTO Tliqconcept (Tliqconcept_nombre, Tliqconcept_tipodoc, Tliqconcept_valor, Tliqconcept_smlv, Tliqconcept_fechaini, Tliqconcept_fechafin, Tliqconcept_terceros, Tliqconcept_porcentaje, Tliqconcept_operacion, Tliqconcept_repetir, Tliqconcept_tramite, Tliqconcept_liq, Tliqconcept_fecha, Tliqconcept_user, Tliqconcept_IPC, Tliqconcept_decreto, Tliqconcept_infraccion, Tliqconcept_ayudas, Tliqconcept_clase, Tliqconcept_fechainif, Tliqconcept_fechafinf, Tliqconcept_ppi, Tliqconcept_ppf) SELECT Tconceptos_nombre, Tconceptos_tipodoc, " . $_POST[$row_rna[0] . "_" . $row_rnc[0]] . ", 0, Tconceptos_fechaini, Tconceptos_fechafin, Tconceptos_terceros, Tconceptos_porcentaje, Tconceptos_operacion, Tconceptos_repetir, " . $row_rna[0] . ", '" . $_POST['liquidacion'] . "', '" . $_POST['fecha'] . "', '" . $_SESSION['MM_Username'] . "', Tconceptos_IPC, Tconceptos_decreto, Tconceptos_infraccion, Tconceptos_ayudas, Tconceptos_clase, Tconceptos_fechainif, Tconceptos_fechafinf, Tconceptos_ppi, Tconceptos_ppf FROM Tconceptos WHERE Tconceptos_ID=" . $row_rnc[0] . ";\n";
                $valor_rnc += $_POST[$row_rna[0] . "_" . $row_rnc[0]];
                $subtotal += $_POST[$row_rna[0] . "_" . $row_rnc[0]];
            }
        }
        if ($valor_rnc > 0) {
            $query_insert_rna .= "UPDATE Tliquidaciontramites SET Tliquidaciontramites_valor=" . $valor_rnc . " WHERE Tliquidaciontramites_liq=" . $_POST['liquidacion'] . " AND Tliquidaciontramites_tramite=" . $row_rna[0] . ";\n";
        }
    }
    if ($_POST['tiporecaudo'] == 3) {
        $tipodoc = 1;
    } elseif ($_POST['tiporecaudo'] == 4) {
        $tipodoc = 2;
    }
    $hoy = date("Y-m-d H:i:s");
    $query_insert_rna .= "SET IDENTITY_INSERT Tliquidacionmain ON;\n ";
    $query_insert_rna .= "INSERT INTO Tliquidacionmain (Tliquidacionmain_ID, Tliquidacionmain_fecha, Tliquidacionmain_idciudadano, Tliquidacionmain_idtramitador, Tliquidacionmain_placa, Tliquidacionmain_estado, Tliquidacionmain_nc, Tliquidacionmain_caduca, Tliquidacionmain_subtotal, Tliquidacionmain_iva, Tliquidacionmain_user, Tliquidacionmain_tipodoc, Tliquidacionmain_fecharecaudo) VALUES (" . $_POST['liquidacion'] . ", '" . $hoy . "', '" . $_POST['documento'] . "', '" . $_POST['documento'] . "', '', 3, '', '" . $_POST['fecha'] . "', " . $subtotal . ", 0, '" . $_SESSION['MM_Username'] . "', " . $tipodoc . ", '" . $_POST['fecha'] . "');\n";
    $query_insert_rna .= "INSERT INTO Trecaudos (Trecaudos_liquidacion, Trecaudos_fecharecaudo, Trecaudos_valor, Trecaudos_documento, Trecaudos_fecha, Trecaudos_user) VALUES (" . $_POST['liquidacion'] . ", '" . $_POST['fecha'] . "', " . $subtotal . ", '" . $_POST['documento'] . "', '" . $hoy . "', '" . $_SESSION['MM_Username'] . "');\n";
    $query_insert_rna .= "COMMIT TRANSACTION InsertRNA;\r";
$result_insert_rna=sqlsrv_query( $mysqli,$query_insert_rna, array(), array('Scrollable' => 'buffered'));

    if (!$result_insert_rna) {
        echo "<script>alert(\"La actualizacion no se pudo realizar, revise los valores!!!\");</script>";
        echo "<script>self.location='recaudo_historico.php';</script>";
    } else {
        echo "<script>alert(\"La actualizacion se ejecutó exitosamente.\");</script>";
    }
}//Tiporecaudo==3
		}//$_POST['guardar']
?>

<link rel="stylesheet" type="text/css" href="omprobar_disponibilidad_de_apodo.css">
<script type="text/javascript" src="comprobar_disponibilidad_de_apodo.js"></script>
<script type="text/javascript" src="comprobar_disponibilidad_liquidacion.js"></script>

<script languaje="javascript"> 

 function expandCollapseTable(tableObj)
{
    var rowCount = tableObj.rows.length;
    for(var row=1; row<rowCount; row++)
    {
        rowObj = tableObj.rows[row];
        rowObj.style.display = (rowObj.style.display=='none') ? '' : 'none';
    }
    return;
}

function sumar(){


if 	(document.getElementById('134').value!=0 || document.getElementById('134').value=="")//Desabilito pronto pago 5 dias si pronto pago 15 dias tiene valor
		{document.getElementById('54').disabled=true;} else {document.getElementById('54').disabled=false;}

if 	(document.getElementById('54').value!=0 || document.getElementById('54').value=="")//Desabilito pronto pago 15 dias si pronto pago 5 dias tiene valor
		{document.getElementById('134').disabled=true;} else {document.getElementById('134').disabled=false;}
			
var comparendo = parseInt(document.getElementById('1000000022').value);
var amn_int_mora= parseInt(document.getElementById('1000000050').value);
var amn_comp_15= parseInt(document.getElementById('134').value);
var amn_comp_5= parseInt(document.getElementById('54').value);
var amn_hon_comp= parseInt(document.getElementById('1000000051').value);
var gastos_cobr= parseInt(document.getElementById('1000000020').value);
var honorarios= parseInt(document.getElementById('1000000016').value);
var int_mora= parseInt(document.getElementById('1000000021').value);

document.form1.total.value=(comparendo+honorarios+int_mora+gastos_cobr)-(amn_int_mora+amn_comp_15+amn_comp_5+amn_hon_comp);//Sumo y resto valores en el campo total
} 

function clicker(bot){
document.getElementById(bot).dblclick();
}

var nav4 = window.Event ? true : false;
function IsNumber(evt){
// Backspace = 8, Enter = 13, ’0′ = 48, ’9′ = 57, ‘.’ = 46
var key = nav4 ? evt.which : evt.keyCode;
return (key <= 13 || (key >= 48 && key <= 57) || key == 46);
}

function checarcombo(){
if(form1.tiporecaudo.value==3 || form1.tiporecaudo.value==4){
    form1.documento_tipo.disabled=true;
	form1.documento_tipo.value="";
	form1.buscar.focus();
}else{
    form1.documento_tipo.disabled=false;
}
}

</script>
<script src="../JSCal2-1.9/src/js/jscal2.js"></script>
<script src="../JSCal2-1.9/src/js/lang/es.js"></script>
<style type="text/css">

body {
	background-image: url(../images/<?php echo $row_param[1]; ?>);
}
.style1 {
	color: #FF0000;
	font-weight: bold;
	font-size: 14px;
}
</style>



</head>



<div class="card container-fluid">
    <div class="header">
        <h2>Recaudo historico</h2>
    </div>
    <br>
	  <form id="form1" name="form1" method="post" action="recaudo_historico.php">



  <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
					<strong>Tipo y numero de documento <span class="style1">*</span>: </strong>
					<br />
				<select name="tiporecaudo" id="tiporecaudo"  class="form-control" OnChange="checarcombo(); document.form1.documento_tipo.focus();">
								<option value="1" >Acuerdo de pago</option>
								<option value="2" <?php if ($_POST['tiporecaudo']==2){echo " selected ";} ?>>Comparendos</option>
								<option value="3" <?php if ($_POST['tiporecaudo']==3){echo " selected ";} ?>>Tramites RNA</option>
								<option value="4" <?php if ($_POST['tiporecaudo']==4){echo " selected ";} ?> >Tramites RNC</option>
							</select>
							
							</div></div></div>
							
							
							  <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
                                 <br>
					    <input class='form-control' name="documento_tipo"  class="form-control" placeholder="Numero de documento (Tambien lo buscara por Identificacion)" type="text" id="documento_tipo" size="15" maxlength="15" <?php if ($_POST['documento_tipo']){echo "value=\"".$_POST['documento_tipo']."\"";}?> >
					    
					    </div></div></div>
					      <div class="col-md-12"> 
					      <div class="col-md-6"> 
                             <div class="form-group form-float">  
                             <div class="form-line">
						<input class='form-control' name="buscar" class="form-control btn btn-success" type="submit" value="Buscar" >
						    </div></div></div></div>
			
			   <?php
		
							if (@$result_ap >= 1 && @$_POST['tiporecaudo'] == 1) {
    $query_linea = "SELECT TAcuerdop_comparendo, TAcuerdop_periodicidad, TAcuerdop_identificacion, TAcuerdop_cuota, TAcuerdop_valor, TAcuerdop_fechapago, TAcuerdop_estado FROM acuerdos_pagos WHERE TAcuerdop_numero='" . $_POST['documento_tipo'] . "'";
    $result_linea=sqlsrv_query( $mysqli,$query_linea, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    $unavez = 1;
    while ($row_linea = mysqli_fetch_array($result_linea)) {
        if ($unavez == 1) {
            echo "<tr><td align='center' colspan=2><strong>Comparendo</strong></td>";
            echo "<td align='center'><strong>Periodicidad</strong></td>";
            echo "<td align='center' colspan=3><strong>Ciudadano</strong></td></tr>";
            echo "<tr><td align='center' colspan=2>" . $row_linea[0] . "</td>";
            echo "<input name='comparendo' id='comparendo' type='hidden' value='" . $row_linea[0] . "' />";
            echo "<input name='origen' id='origen' type='hidden' value='" . traenombrecampo(Tcomparendos, Tcomparendos_comparendo, Tcomparendos_origen, Tcomparendos_origen, $row_linea[0]) . "' />";
            echo "<td align='center'>" . traenombrecampo(TAcuerdop_period, TAcuerdop_period_id, TAcuerdop_period_nombre, TAcuerdop_period_nombre, $row_linea[1]) . "</td>";
            $identificacion = "'" . trim($row_linea[2]) . "'";
            echo "<input name='documento' id='documento' type='hidden' value=" . $identificacion . "  />";
            echo "<td align='center' colspan=3>" . traenombrecampo(Tciudadanos, Tciudadanos_ident, "Tciudadanos_nombres+' '+Tciudadanos_apellidos", Tciudadanos_nombres, $identificacion) . "</td></tr>";
            echo "<tr><td colspan=7></br><strong>1. Solo se podran recaudar las cuotas no recaudadas.</br>2. Los campos: Valor cuota, Fecha Recaudo, Liquidacion y Recaudo, son obligatorios.</br>3. Solo se recaudaran las cuotas chequeadas a recaudar.</br>4. Una vez recaudada una cuota no se puede cambiar el estado.</strong></p></td></tr>";
            echo "<tr><td align='center'><strong>Cuota</strong></td>";
            echo "<td align='center'><strong>Valor Cuota</strong></td>";
            echo "<td align='center'><strong> Fecha de pago </strong></td>";
            echo "<td align='center'><strong>Fecha de recaudo</strong></td>";
            echo "<td align='center'><strong>Liquidacion</strong></td>";
            echo "<td align='center'><strong>Recaudar</strong></td></tr>";
            $unavez++;
        }
        echo "<tr><td align='center'>" . $row_linea[3] . "</td>";
        if ($row_linea[6] == 2) {
            echo "<td align='center'>$" . number_format($row_linea[4]) . "</td>";
        } else {
            echo "<td align='center'><input name='valor_" . $row_linea[3] . "' type='text' size='10' maxlength='10' value='" . $row_linea[4] . "'></td>";
        }
        echo "<td align='center'>" . $row_linea[5] . "</td>";
        if ($row_linea[6] == 2) {
            echo "<td align='center'>----------</td>";
        } else {
            $input_name = "fecha_recaudo_" . $row_linea[3];
            $button_name = "cal_fecha_AP_" . $row_linea[3];
            echo "<td align='center'>";
            echo "<input name=\"" . $input_name . "\" type=\"text\" id=\"" . $input_name . "\" size=\"10\" placeholder=\"YYYY-mm-dd\"  /><button name=\"" . $button_name . "\" type=\"button\" id=\"" . $button_name . "\" onmouseover=\"Calendar.setup({inputField:'" . $input_name . "',trigger:'" . $button_name . "',onSelect:function(){this.hide()},showTime:12,dateFormat:'%Y-%m-%d'})\" onclick=\"Calendar.setup({inputField:'" . $input_name . "',trigger:'" . $button_name . "',onSelect:function(){this.hide()},showTime:12,dateFormat:'%Y-%m-%d'})\" style=\"width:30;height:25;vertical-align:middle\"><img src=\"../images/imagemenu/fecha.png\" alt=\"Fecha\" width=\"15\" height=\"18\" onmouseover=\"Tip('Haga clic para seleccionar la fecha')\" onmouseout=\"UnTip()\" /></button>";
            echo "</td>";
        }
        if ($row_linea[6] == 2) {
            echo "<td align='center'>----------</td>";
        } else {
            echo "<td align='center'><input name='liq_no_" . $row_linea[3] . "' size=10 type='text' /> </td>";
        }
        if ($row_linea[6] == 2) {
            echo "<td align='center'><span class='style1'>Recaudado</span></td>";
        } else {
            echo "<td align='center'><input name='recaudar_cuota_" . $row_linea[3] . "' type='checkbox' value=1 /> </td>";
        }
        echo "</tr>";
    }
    echo "<input name='documento' id='documento' type='hidden' value=" . $row_linea[2] . " />";
    echo "<input name='fecha_caduca' id='fecha_caduca' type='hidden' value=" . $row_linea[5] . " />";
    echo "</tr>";
} elseif (@$result_ap >= 2 && @$_POST['tiporecaudo'] == 1) {
    $query_ap = "SELECT TAcuerdop_numero FROM acuerdos_pagos WHERE TAcuerdop_numero='" . trim($_POST['documento_tipo']) . "' OR TAcuerdop_identificacion= '" . trim($_POST['documento_tipo']) . "' GROUP BY TAcuerdop_comparendo, TAcuerdop_numero ORDER BY TAcuerdop_numero";
    $result_ap=sqlsrv_query( $mysqli,$query_ap, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    echo "<tr><td align='center' colspan=7></p><strong>Se encontraron varios AP's, seleccione uno para realizar el recaudo:</strong></p>";
    while ($row_ap = mysqli_fetch_array($result_ap)) {
        ?>
        <button type="button" name="boton" id="botonVerificacion" onClick="document.getElementById('documento_tipo').value=<?php echo $row_ap['TAcuerdop_numero']; ?>;"><?php echo "<strong>Acuerdo de Pago " . $row_ap['TAcuerdop_numero'] . "</strong>"; ?></button>
        <?php
    }
    echo "</td></tr>";
}

if ($result_comp == 1 && $_POST['tiporecaudo'] == 2) {
    $query_linea = "SELECT Tcomparendos_comparendo, Tcomparendos_fecha,  Tcomparendos_placa, Tcomparendos_codinfraccion, Tcomparendos_estado, Tcomparendos_idinfractor, Tcomparendos_origen FROM comparendos WHERE Tcomparendos_comparendo='" . $_POST['documento_tipo'] . "' or Tcomparendos_idinfractor=" . $_POST['documento_tipo'] . " AND Tcomparendos_estado NOT IN (2, 3, 4)";
    $result_linea=sqlsrv_query( $mysqli,$query_linea, array(), array('Scrollable' => 'buffered'));
    echo "<table class='table'><tr><td colspan=7></br><strong>1. Los campos: Valor recaudo y Fecha Recaudo, son obligatorios.</br>2. El comparendo quedara en estado recaudado.</br></strong></p></td></tr>";
    echo "<tr><td align='center'><strong>Comparendo</strong></td>";
    echo "<td align='center'><strong>Fecha</strong></td>";
    echo "<td align='center'><strong> Placa </strong></td>";
    echo "<td align='center'><strong>Infraccion</strong></td>";
    echo "<td align='center'><strong>Estado</strong></td>";
    echo "<td align='center'><strong>Infractor</strong></td></tr>";
    while ($row_linea = mysqli_fetch_array($result_linea)) {
        echo "<tr><td align='center'>" . $row_linea[0] . "</td>";
        echo "<td align='center'>" . $row_linea[1] . "</td>";
        echo "<td align='center'>" . $row_linea[2] . "</td>";
        echo "<td align='center'>" . $row_linea[3] . "</td>";
        echo "<input class='form-control' name='documento' id='documento' type='hidden' value=" . $row_linea[3] . " />";
        if ($row_linea[4] == 1) {
            $estado_comp = "Activo";
        }
        switch ($row_linea[4]) {
            case 1:
                $estado_comp = "Activo";
                break;
            case 2:
                $estado_comp = "Recaudado";
                break;
            case 3:
                $estado_comp = "Acuerdo de pago";
                break;
            case 4:
                $estado_comp = "Preacuerdo";
                break;
            case 5:
                $estado_comp = "Vencido";
                break;
            case 6:
                $estado_comp = "Sancionado";
                break;
        }
        echo "<td align='center'>" . $estado_comp . "</td>";
        echo "<input name='comparendo' type='hidden' value='" . $row_linea[0] . "' />";
        echo "<input name='fecha_comparendo' type='hidden' value='" . $row_linea[1] . "' />";
        echo "<input name='placa' type='hidden' value='" . $row_linea[2] . "' />";
        echo "<input name='infractor' type='hidden' value='" . $row_linea[5] . "' />";
        echo "<input name='origen' id='origen' type='hidden' value=" . $row_linea[6] . " />";
        echo "<td align='center'>" . $row_linea[5] . "</td></tr>";
    }
    echo "<tr><td colspan=2><label><strong>Liquidacion<span class='style1'>* (no debe existir)</span>:</strong></label></td>";
    echo "<td colspan=4>";
    include('comprobar_disponibilidad_liquidacion.php');
    echo "</td>/<tr>";
    ?>
    <tr>
        <td colspan=2><strong>Fecha de recaudo<span class="style1">*</span>: </strong></td>
        <td colspan=4>
            <input class='form-control' name="fecha" type="text" id="fecha" size="15" readonly />
        </td>
    </tr>
    <?php
    echo "<tr><td colspan=6 align='center'><span class='style1'><strong></p>RECAUDO</span></strong></td></tr>";
    echo "<tr><td colspan=2 align='center'><strong>Concepto</strong></td>";
    echo "<td colspan=2 align='center'><strong>Valor</strong></td>";
    echo "<td colspan=2 align='center'><strong>Tercero</strong></td></tr>";
    echo "<tr><td colspan=2><strong>Valor Comparendo (Neto)<span class='style1'>*</span>:</strong></td>";
    echo "<td colspan=2 align='center'><input class='form-control' name='1000000022' id='1000000022' type='text' size='10' maxlength='10' value=0  Onchange='sumar()' Onkeyup='sumar()'  Onblur='sumar()' /></td><td colspan=2>";
    //Llamado a funcion traenombrecampo
    //Parametros: $Tabla (Nombre Tabla), $campo1 (Campo ID), $campo2 (Campo a mostrar), $campo_order (Campo para ordenar), $condicion (Campo where)
    echo traenombrecampo(terceros, id, nombre, nombre, 91100164);//Imprime Estado
    echo"</td></tr>";
    $query_conceptos = "SELECT Tconceptos_ID, Tconceptos_nombre, Tconceptos_terceros, Tconceptos_origen, Tterceros_nombre
        FROM Tconceptos INNER JOIN Tterceros ON Tconceptos_terceros = Tterceros_ID
        WHERE (Tconceptos_tipodoc = 4 OR Tconceptos_tipodoc = 3) AND (Tconceptos_ID IN (1000000016, 1000000020, 1000000021, 93, 94, 134, 1000000050, 1000000051, 54/*, 38, 51, 52, 53*/))
        ORDER BY Tconceptos_nombre";
    $result_conceptos=sqlsrv_query( $mysqli,$query_conceptos, array(), array('Scrollable' => 'buffered'));
    while ($row_conceptos = mysqli_fetch_array($result_conceptos)) {
        echo "<tr><td colspan=2><strong>" . $row_conceptos[1] . "</strong></td>";
        echo "<td colspan=2 align='center'><input class='form-control' name='" . $row_conceptos['Tconceptos_ID'] . "' id='" . $row_conceptos['Tconceptos_ID'] . "' type='text' size='10' maxlength='10' value=0   Onchange='sumar()' Onkeyup='sumar()' Onblur='sumar()' onkeypress=\"return IsNumber(event);\"/>";
        echo "<td colspan=2>" . $row_conceptos['Tterceros_nombre'] . "</td></tr>";
    }
    ?>
    <tr>
        <td colspan=2 align="center"><strong><font size="3"> TOTAL: </font></strong></td>
        <td colspan=2  align='center'><input class='form-control' name="total" type="text" id="total" size="10" readonly /></td>
    </tr>
    <?php
} elseif ($result_comp >= 2 && $_POST['tiporecaudo'] == 2) { // si tiene algun resultado 
    $query_comp = "SELECT Tcomparendos_comparendo FROM comparendos WHERE Tcomparendos_comparendo='" . $_POST['documento_tipo'] . "' or Tcomparendos_idinfractor=" . $_POST['documento_tipo'] . " AND Tcomparendos_estado NOT IN (2, 3, 4) GROUP BY Tcomparendos_comparendo ORDER BY Tcomparendos_comparendo";
    $result_comp=sqlsrv_query( $mysqli,$query_comp, array(), array('Scrollable' => 'buffered')) or die(guardar_error(__LINE__));
    echo "<tr><td align='center' colspan=7></p><strong>Se encontraron varios comparendos para el ciudadano,</br>seleccione uno para realizar el recaudo:</strong></p>"; //Imprime AP
    while ($row_comp = mysqli_fetch_array($result_comp)) {
        ?>
        <button type="button" name="boton" id="botonVerificacion" onClick="document.getElementById('documento_tipo').value=<?php echo $row_comp['Tcomparendos_comparendo']; ?>;"><?php echo "<strong>Comparendo  " . $row_comp['Tcomparendos_comparendo'] . "</strong>"; ?></button>
        <?php
    }
    echo "</td></tr>";
}

if ($_POST['tiporecaudo'] == 3 || $_POST['tiporecaudo'] == 4) {
    echo "</tr>";
    echo "<tr><td colspan=2><label><strong>Ciudadano:<span class='style1'>* (debe existir)</span>:</strong></label></td>";
    echo "<td colspan=4>";
    include('../funciones/find/comprobar_disponibilidad_de_apodo.php');
    echo "</td>/<tr>";
    echo "<tr><td colspan=2><label><strong>Liquidacion<span class='style1'>* (no debe existir)</span>:</strong></label></td>";

    echo "<td colspan=4>";
    include('comprobar_disponibilidad_liquidacion.php');
    echo "</td>/<tr>";
    ?>
    <tr>
        <td colspan=2><strong>Fecha de recaudo<span class="style1">*</span>: </strong></td>
        <td colspan=4>
            <input class='form-control' name="fecha" type="text" id="fecha" size="15" readonly /><button name="cal-fecha" type="button" id="cal-fecha" onmouseover="Calendar.setup({inputField:'fecha',trigger:'cal-fecha',onSelect:function(){this.hide()},showTime:12,dateFormat:'%Y-%m-%d'})" onclick="Calendar.setup({inputField:'fecha',trigger:'cal-fecha',onSelect:function(){this.hide()},showTime:12,dateFormat:'%Y-%m-%d'})" style="width:30;height:25;vertical-align:middle"><img src="../images/imagemenu/fecha.png" alt="Fecha" width="15" height="18" onmouseover="Tip('Haga clic para seleccionar la fecha')" onmouseout="UnTip()" /></button> </td>
    </tr>
    <?php
}

if ($_POST['tiporecaudo'] == 3) {
    echo "<tr><td colspan=5 align='right'> <strong>Doble click para Contraer/Expandir</strong>";
    $query_rna = "SELECT id, nombre FROM tramites WHERE tipo_documento =1 order by nombre";
    $result_rna=sqlsrv_query( $mysqli,$query_rna, array(), array('Scrollable' => 'buffered'));
    $posicion = 0;
    $array = array();
    while ($row_rna = mysqli_fetch_array($result_rna)) {
        ?>
        <table width="100%" border="0" id="myTable" ondblclick="expandCollapseTable(this)">
            <tr id="tr1" bordercolor="#FFFFFF">
                <th colspan="2" width="95%" align="left"><?php echo $row_rna['nombre']; ?></th>
                <th align="right"><input class='form-control' align="right" id="<?php echo $row_rna['id']; ?>" type="button" value="[-] [+] " ondblclick="expandCollapseTable(this);"></th>
            </tr>
            <?php

            $array[$posicion] = $row_rna['Ttramites_ID'];
            $posicion++;
            $query_rnc = "SELECT id, nombre FROM conceptos WHERE id IN (SELECT concepto_id FROM detalle_tramites WHERE tramite_id=" . $row_rna['id'] . ")";
            $result_rnc=sqlsrv_query( $mysqli,$query_rnc, array(), array('Scrollable' => 'buffered'));

            while ($row_rnc = mysqli_fetch_array($result_rnc)) {
                echo "<tr class='gradient'><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;" . $row_rnc['nombre'] . "</td><td><input class='form-control' name='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' id='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' type='text' size=5></td></tr>";
            }
            ?>
        </table>
        <?php
    }
    foreach ($array as $valor) {
        ?>
        <script type="text/javascript">
            clicker(<?php echo $valor; ?>);
        </script>
        <?php
    }
    echo "</td></tr>";
}

if ($_POST['tiporecaudo'] == 4) {
    echo "<tr><td colspan=5 align='right'> <strong>Doble click para Contraer/Expandir</strong>";
    $query_rna = "SELECT id, nombre FROM tramites WHERE tipo_documento=2 order by nombre";
    $result_rna=sqlsrv_query( $mysqli,$query_rna, array(), array('Scrollable' => 'buffered'));
    $posicion = 0;
    $array = array();
    while ($row_rna = mysqli_fetch_array($result_rna)) {
        ?>
        <table width="100%" border="0" id="myTable" ondblclick="expandCollapseTable(this)">
            <tr id="tr1" bordercolor="#FFFFFF">
                <th colspan="2" width="95%" align="left"><?php echo $row_rna['Ttramites_nombre']; ?></th>
                <th align="right"><input class='form-control' align="right" id="<?php echo $row_rna['id']; ?>" type="button" value="[-] [+] " ondblclick="expandCollapseTable(this);"></th>
            </tr>
            <?php
            $array[$posicion] = $row_rna['id'];
            $posicion++;
            $query_rnc = "SELECT id, nombre FROM conceptos WHERE id IN (SELECT concepto_id FROM detalle_tramites WHERE tramite_id=" . $row_rna['id'] . ")";
            $result_rnc=sqlsrv_query( $mysqli,$query_rnc, array(), array('Scrollable' => 'buffered'));
            while ($row_rnc = mysqli_fetch_array($result_rnc)) {
                echo "<tr class='gradient'><td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;" . $row_rnc['nombre'] . "</td><td><input class='form-control' name='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' id='" . $row_rna['id'] . "_" . $row_rnc['id'] . "' type='text' size=5></td></tr>";
            }
            ?>
        </table>
        <?php
    }
    foreach ($array as $valor) {
        ?>
        <script type="text/javascript">
            clicker(<?php echo $valor; ?>);
        </script>
        <?php
    }
    echo "</td></tr>";
}
							
														
							if ((@$result_comp==1 or @$result_ap==1) and $_POST[buscar] or (@$_POST['tiporecaudo']==3 or @$_POST['tiporecaudo']==4)) //Imprime el boton Guardar y la consulta es exitosa.
							{
								echo "<tr><td colspan='7' align='center'><input class='form-control' name='guardar' type='submit' value='Guardar' /></td></tr>";
							}
			   	?>		
				 

              			  
			  				
              
        </table>
		</form>
      </div>
</div>

<?php include 'scripts.php'; ?>