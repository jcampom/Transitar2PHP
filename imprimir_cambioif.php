<?php

session_start();
require_once('../Connections/transito_conect.php');
require_once('../funciones/funciones.php');
RestricSession();
include("../MPDF54/mpdf.php");
date_default_timezone_set("America/Bogota");
setlocale(LC_TIME, 'spanish');

if (isset($_GET['ref_not']) and is_numeric($_GET['ref_not'])) {
    $datos = true;
    $sql = "SELECT Tcomparendos_comparendo as comparendo, Tcomparendos_fecha AS fechacomp, 
            Tcomparendos_codinfraccion AS codigo, Tcomparendos_placa AS placa,
            Tcomparendos_fecha AS fechacomp, Tcomparendos_lugar AS direccion,
            N.presente, N.nauto ,cast(fecha as date) as fecha
        FROM Tcomparendos C INNER JOIN notificaciones N ON Tcomparendos_ID = N.compId
        WHERE N.id = " . $_GET['ref_not'];
    $sql_query = mssql_query($sql) or die("Verifique el nombre de la tabla");
    if (mssql_num_rows($sql_query) > 0) {
        $rownot = mssql_fetch_array($sql_query);
        $presente = $rownot['presente'];
        $sino = $presente ? 'SI' : 'NO';
        $comparendo = $rownot['comparendo'];
        $ncomparendo = gen_num_comparendo($comparendo);
        $numero = $_GET['ref_not'];
        $fecha_base = strftime("%d de %B de %Y", strtotime($rownot['fecha']));
        $fecha = strftime("%d de %B de %Y a las %H:%M", strtotime($rownot['fechacomp']));

        $ciudadano = "SELECT C1.Tciudadanos_nombres + ' ' + C1.Tciudadanos_apellidos AS nombre,
                T1.Ttipoidentificacion_nombre tipoident, C1.Tciudadanos_ident AS ident,
                C2.Tciudadanos_nombres + ' ' + C2.Tciudadanos_apellidos AS nnombre,
                T2.Ttipoidentificacion_nombre ntipoident, C2.Tciudadanos_ident AS nident,
                C2.Tciudadanos_direccion AS direccion, Tciudades_nombre AS ciudad,
                D.Tdepartamentos_nombre AS depart, C2.Tciudadanos_telfijo AS telefono
            FROM notificaciones N 
                INNER JOIN Tciudadanos C1 ON C1.Tciudadanos_ID = infant
                INNER JOIN Ttipoidentificacion T1 ON C1.Tciudadanos_tipoid = T1.Ttipoidentificacion_ID  
                INNER JOIN Tciudadanos C2 ON C2.Tciudadanos_ID = infnew
                INNER JOIN Ttipoidentificacion T2 ON C2.Tciudadanos_tipoid = T2.Ttipoidentificacion_ID  
                INNER JOIN Tciudades C ON C2.Tciudadanos_cr = C.Tciudades_ID
                INNER JOIN Tdepartamentos D ON D.Tdepartamentos_ID = C.Tciudades_departamento
            WHERE N.id = " . $_GET['ref_not'];
        $query_ciudadano = mssql_query($ciudadano) or die("Verifique el nombre de la tabla");
        if (mssql_num_rows($query_ciudadano) > 0) {
            $row_ciud = mssql_fetch_assoc($query_ciudadano);
            $nnombre = toUTF8($row_ciud['nnombre']);
            $nombre = toUTF8($row_ciud['nombre']);
        } else {
            $datos = false;
        }
    } else {
        $datos = false;
    }

    if ($datos) {
        $firmaCenter = true;
        include_once("./gdp_notfica_hff.php");
        $mpdf = new mPDF('en-x', 'letter', '', '', 25, 25, 35, 18, 10, 10);
        $mpdf->WriteHTML($styles, 1);
        $mpdf->SetHTMLHeader($header, 'O');
        $mpdf->SetHTMLFooter($footer, 'O');
        $html = '
		<head><title>Constancia de Actualizacion</title><head>
		<body>
            <h3 style="text-align:center">ACTA AUDIENCIA DE VINCULACION A LOCATARIO Y/O CONDUCTOR</h3>
            <p>&nbsp;</p>
            <p><strong>Orden de Comparendo No.:</strong> ' . $ncomparendo . '<br />
            <strong>C&oacute;digo de la Infracci&oacute;n:</strong> ' . $rownot['codigo'] . '<br />
            <strong>Placa:</strong> ' . $rownot['placa'] . '<br />
            <strong>Fecha de Ocurrencia de los hechos: ' . $fecha . '</strong><br/>
            <strong>Lugar de Ocurrencia de los hechos: ' . toUTF8($rownot['direccion']) . '</strong><br/>
            <p>&nbsp;</p>
            <p style="text-align:justify">En el Municipio de ' . $municipio . ', siendo el d&iacute;a ' . $fecha_base . ', se lleva a cabo audiencia p&uacute;blica con ocasi&oacute;n a la orden de comparendo de la referencia, encontr&aacute;ndose debidamente notificada conforme a lo establecido en el art&iacute;culo 135 de la Ley 769 de 2002, modificado por el art&iacute;culo 22 de la Ley 1383 de 2010 y articulo 8 de la Ley 1843 de 2017. Acto seguido y teniendo en cuenta la necesidad de garantizar la comparecencia, ante la autoridad de tr&aacute;nsito del se&ntilde;or (a) ' . $nombre . ', identificado con ' . $row_ciud['tipoident'] . ' No. ' . trim($row_ciud['ident']) . ', en su calidad de propietario del veh&iacute;culo de placa ' . trim($rownot['placa']) . ', dentro del Proceso contravencional del comparendo No. ' . $ncomparendo . '. Procede este despacho a solicitar la informaci&oacute;n de la persona que conduc&iacute;a el veh&iacute;culo de placa ' . $rownot['placa'] . ' para la fecha de la infracci&oacute;n, con la finalidad de establecer la responsabilidad del conductor del veh&iacute;culo.</p>
            <p style="text-align:justify"><strong>PREGUNTA:</strong> S&iacute;rvase decir al despacho que tiene que decir con respecto a la orden de comparendo de la referencia. <strong>RESPUESTA:</strong> Manifiesto bajo la gravedad de juramento y presento ante esta diligencia soportes que se constituyen en pruebas para demostrar y certificar la responsabilidad del se&ntilde;or (a) ' . $nnombre . ' identificado con la ' . $row_ciud['ntipoident'] . ' No. ' . $row_ciud['nident'] . ', en calidad de locatario y/o conductor del veh&iacute;culo de placa ' . $rownot['placa'] . ' para la fecha de la infracci&oacute;n de tr&aacute;nsito. <strong>PREGUNTA</strong>: S&iacute;rvase decir al despacho, si el se&ntilde;or(a) ' . $nnombre . ' se encuentra presente. <strong>RESPUESTA</strong>: ' . $sino . '. ';
        if (!$presente) {
            $html .= '<span> <strong>PREGUNTA</strong>: S&iacute;rvase decir al despacho si cuenta con alg&uacute;n documento que soporte la direcci&oacute;n de notificaciones del Se&ntilde;or(a) ' . $nnombre . ', para efecto de notificaci&oacute;n. <strong>RESPUESTA</strong>: Si, aporto documento donde consta que la direcci&oacute;n para notificar es en la ' . toUTF8($row_ciud['direccion']) . ' en ' . toUTF8($row_ciud['ciudad']) . '-' . toUTF8($row_ciud['depart']) . ' y tel&eacute;fono ' . $row_ciud['telefono'] . '.</span> ';
        }
        $html .= '<strong>PREGUNTA</strong>: S&iacute;rvase decir al despacho, si tiene algo m&aacute;s que agregar, corregir o enmendar. <strong>RESPUESTA</strong>: No.</p>';
        if ($presente) {
            $html .= '<p><span>En este estado de la diligencia y encontr&aacute;ndose presente el presunto conductor del veh&iacute;culo de placa ' . $rownot['placa'] . ', con la finalidad de garantizar el debido proceso y derecho a la defensa que le asiste al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . ', procede el despacho a vincular al tr&aacute;mite contravencional o procedimiento administrativo sancionatorio, al locatario y/o conductor del veh&iacute;culo en menci&oacute;n, y se le realizan las siguientes: <strong>PREGUNTA</strong>: S&iacute;rvase decir el declarante su nombre completo y n&uacute;mero de identificaci&oacute;n. <strong>RESPUESTA</strong>: Mi nombre es ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . $row_ciud['nident'] . '. <strong>PREGUNTA</strong>: S&iacute;rvase decir el declarante si usted era la persona que conduc&iacute;a el veh&iacute;culo de placa ' . $rownot['placa'] . ' el d&iacute;a ' . $fecha . '. <strong>RESPUESTA</strong>: Si, yo era la persona que conduc&iacute;a el veh&iacute;culo. <strong>PREGUNTA</strong>: S&iacute;rvase decir el declarante la direcci&oacute;n de residencia para efectos de notificaci&oacute;n. <strong>RESPUESTA</strong>: Mi direcci&oacute;n de notificaci&oacute;n es ' . toUTF8($row_ciud['direccion']) . ' en ' . toUTF8($row_ciud['ciudad']) . '-' . toUTF8($row_ciud['depart']) . '. <strong>PREGUNTA</strong>: S&iacute;rvase decir al despacho si tiene algo m&aacute;s que agregar, corregir o enmendar a su declaraci&oacute;n. <strong>RESPUESTA</strong>: No.</span></p>';
        }
        $html .= '<p style="text-align:justify">En este estado de la diligencia procede el despacho a vincular al tr&aacute;mite contravencional o procedimiento administrativo sancionatorio, al locatario y/o conductor del veh&iacute;culo en menci&oacute;n, profiriendo el siguiente,</p>
            <h3 style="text-align:center">AUTO No. ' . $rownot['nauto'] . '</h3>
            <p style="text-align:justify">El (La) suscrito(a) ' . $firmaCargo . ' CON FUNCIONES DE INSPECTOR DE TR&Aacute;NSITO EN ASUNTOS DE COMPARENDOS ELECTR&Oacute;NICOS DE ' . mb_strtoupper($insTransito, 'utf-8') . ', en uso de sus facultades legales y constitucionales y</p>
            <h3 style="text-align:center">CONSIDERANDO</h3>
            <p style="text-align:justify">Que el art&iacute;culo 8 de la Ley 1843 de 2017, establece que el propietario del veh&iacute;culo ser&aacute; solidariamente responsable con el conductor, previa vinculaci&oacute;n al proceso contravencional de la siguiente manera:</p>
            <p style="text-align:justify">&ldquo;<em>Procedimiento ante la comisi&oacute;n de una contravenci&oacute;n detectada por el sistema de ayudas tecnol&oacute;gicas, la autoridad de tr&aacute;nsito debe seguir el procedimiento que se describe a continuaci&oacute;n.</em></p>
            <p style="text-align:justify"><em>El env&iacute;o se har&aacute; por correo y/o correo electr&oacute;nico, en el primer caso a trav&eacute;s de una empresa de correos legalmente constituida, dentro de los tres (3) d&iacute;as h&aacute;biles siguientes a la validaci&oacute;n del comparendo por parte de la autoridad, copia del comparendo y sus soportes al propietario del veh&iacute;culo y a la empresa a la cual se encuentra vinculado; este &uacute;ltimo caso, en el evento de que se trate de un veh&iacute;culo de servicio p&uacute;blico, En el evento en que no sea posible identificar al propietario del veh&iacute;culo en la &uacute;ltima direcci&oacute;n registrada en el RUNT , la autoridad deber&aacute; hacer el proceso de notificaci&oacute;n por aviso de la orden de comparendo. Una vez allegada a la autoridad de tr&aacute;nsito del respectivo ente territorial donde se detect&oacute; la infracci&oacute;n con ayudas tecnol&oacute;gicas se le enviar&aacute; al propietario del veh&iacute;culo la orden de comparendo y sus soportes en la que ordenar&aacute; presentarse ante la autoridad de tr&aacute;nsito competente dentro de los once (11) d&iacute;as h&aacute;biles siguientes a la entrega del comparendo, contados a partir del recibo del comparendo en la &uacute;ltima direcci&oacute;n registrada por el propietario del veh&iacute;culo en el Registro &Uacute;nico Nacional de Tr&aacute;nsito, para el inicio del proceso contravencional, en los t&eacute;rminos del C&oacute;digo Nacional de Tr&aacute;nsito.</em></p>
            <p style="text-align:justify"><em>Par&aacute;grafo 1. El propietario del veh&iacute;culo ser&aacute; solidariamente responsable con el conductor, previa su vinculaci&oacute;n al proceso contravencional, a trav&eacute;s de la notificaci&oacute;n del comparendo en los t&eacute;rminos previstos en el presente art&iacute;culo, permitiendo que ejerza su derecho de defensa.</em></p>
            <p style="text-align:justify"><em>Par&aacute;grafo 2. Los organismos de tr&aacute;nsito podr&aacute;n suscribir contratos o convenios con entes p&uacute;blicos o privados con el fin de dar aplicaci&oacute;n a los principios de celeridad y eficiencia en el recaudo y cobro de las multas.</em></p>
            <p style="text-align:justify"><em>Par&aacute;grafo 3. Ser&aacute; responsabilidad de los propietarios de veh&iacute;culos actualizar la direcci&oacute;n de notificaciones en el Registro &Uacute;nico Nacional de Tr&aacute;nsito - RUNT, no hacerlo implicar&aacute; que la autoridad enviar&aacute; la orden de comparendo a la &uacute;ltima direcci&oacute;n registrada en el RUNT, quedando vinculado al proceso contravencional y notificado en estrados de las decisiones subsiguientes en el mencionado proceso. La actualizaci&oacute;n de datos del propietario del veh&iacute;culo en el RUNT deber&aacute; incluir como m&iacute;nimo la siguiente informaci&oacute;n:</em></p>
            <p style="text-align:justify"><em>a) Direcci&oacute;n de notificaci&oacute;n;<br />
            b) N&uacute;mero telef&oacute;nico de contacto;<br />
            c) Correo electr&oacute;nico; entre otros, los cuales ser&aacute;n fijados por el Ministerio de Transporte.&rdquo;</em></p>
            <p style="text-align:justify">Por otra parte, el art&iacute;culo 136 de la Ley 769 de 2002, modificado por el art&iacute;culo 205 del Decreto 19 de 2012, a su tenor literal reza</p>
            <p style="text-align:justify"><em>&ldquo;Reducci&oacute;n de la Multa. Una vez surtida la orden de comparendo, si el inculpado acepta la comisi&oacute;n de la infracci&oacute;n, podr&aacute;, sin necesidad de otra actuaci&oacute;n administrativa:</em></p>
            <ol>
                <li style="text-align:justify"><em>Cancelar el cincuenta por ciento (50%) del valor de la multa dentro de los cinco (5) d&iacute;as siguientes a la orden de comparendo y siempre y cuando asista obligatoriamente a un curso sobre normas de tr&aacute;nsito en un Organismo de Tr&aacute;nsito o en un Centro Integral de Atenci&oacute;n. Si el curso se realiza ante un Centro Integral de Atenci&oacute;n o en un organismo de tr&aacute;nsito de diferente jurisdicci&oacute;n donde se cometi&oacute; la infracci&oacute;n, a &eacute;ste se le cancelar&aacute; un veinticinco por ciento (25%) del valor a pagar y el excedente se pagar&aacute; al organismo de tr&aacute;nsito de la jurisdicci&oacute;n donde se cometi&oacute; la infracci&oacute;n; o</em></li>
                <li style="text-align:justify"><em>Cancelar el setenta y cinco (75%) del valor de la multa, si paga dentro de los veinte d&iacute;as siguientes a la orden de comparendo y siempre y cuando asista obligatoriamente a un curso sobre normas de tr&aacute;nsito en un organismo de tr&aacute;nsito o en un Centro Integral de Atenci&oacute;n. Si el curso se realiza ante un Centro Integral de Atenci&oacute;n o en un organismo de tr&aacute;nsito de diferente jurisdicci&oacute;n donde se cometi&oacute; la infracci&oacute;n, a &eacute;ste se le cancelar&aacute; un veinticinco por ciento (25%) del valor a pagar y el excedente se pagar&aacute; al organismo de tr&aacute;nsito de la jurisdicci&oacute;n donde se cometi&oacute; la infracci&oacute;n; o</em></li>
                <li style="text-align:justify"><em>Si aceptada la infracci&oacute;n, &eacute;sta no se paga en las oportunidades antes indicadas, el inculpado deber&aacute; cancelar el cien por ciento (100%) del valor de la multa m&aacute;s sus correspondientes intereses moratorios.</em></li>
            </ol>
            <p style="text-align:justify"><em>Si el inculpado rechaza la comisi&oacute;n de la infracci&oacute;n, deber&aacute; comparecer ante el funcionario en audiencia p&uacute;blica para que &eacute;ste decrete las pruebas conducentes que le sean solicitadas y las de oficio que considere &uacute;tiles.</em></p>
            <p style="text-align:justify"><em>Si el contraventor no compareciere sin justa causa comprobada dentro de los cinco (5) d&iacute;as h&aacute;biles siguientes a la notificaci&oacute;n del comparendo, la autoridad de tr&aacute;nsito, despu&eacute;s de treinta (30) d&iacute;as calendario de ocurrida la presunta infracci&oacute;n, seguir&aacute; el proceso, entendi&eacute;ndose que queda vinculado al mismo, fall&aacute;ndose en audiencia p&uacute;blica y notific&aacute;ndose en estrados.</em></p>
            <p style="text-align:justify"><em>En la misma audiencia, si fuere posible, se practicar&aacute;n las pruebas y se sancionar&aacute; o absolver&aacute; al inculpado. Si fuere declarado contraventor, se le impondr&aacute; el cien por ciento (100%) de la sanci&oacute;n prevista en la ley. Los organismos de tr&aacute;nsito de manera gratuita podr&aacute;n celebrar acuerdos para el recaudo de las multas y podr&aacute;n establecer convenios con los bancos para este fin. El pago de la multa a favor del organismo de tr&aacute;nsito que la impone y la comparecencia, podr&aacute; efectuarse en cualquier lugar del pa&iacute;s.</em>&quot;</p>
            <p>Asimismo, el art&iacute;culo 137 ib&iacute;dem que precept&uacute;a</p>
            <p style="text-align:justify">&ldquo;<em>En los casos en que la infracci&oacute;n fuere detectada por medios que permitan comprobar la identidad del veh&iacute;culo o del conductor el comparendo se remitir&aacute; a la direcci&oacute;n registrada del &uacute;ltimo propietario del veh&iacute;culo. La actuaci&oacute;n se adelantar&aacute; en la forma prevista en el art&iacute;culo precedente, con un plazo adicional de seis (6) d&iacute;as h&aacute;biles contados a partir del recibo de la comunicaci&oacute;n respectiva, para lo cual deber&aacute; disponerse de la prueba de la infracci&oacute;n como anexo necesario del comparendo</em>&rdquo;.</p>
            <p>Que la Corte Constitucional, en Sentencia C-980 de 2010, manifiesta:</p>
            <p style="text-align:justify">&ldquo;<em>En efecto, en las actuaciones de car&aacute;cter particular y concreto que adelanten las autoridades administrativas, antes de imponer la sanci&oacute;n, &eacute;stas tienen la obligaci&oacute;n de garantizar al administrado el derecho fundamental al debido proceso, el cual se concreta: (i) en la posibilidad de ser o&iacute;do durante toda la actuaci&oacute;n y permitir su participaci&oacute;n desde el inicio hasta su culminaci&oacute;n; (ii) en que le sean notificadas todas y cada una de las decisiones que all&iacute; se adoptan; (ii) en que la actuaci&oacute;n se adelante por autoridad competente y con el pleno respeto de las formas propias del juicio; (iv) en que se asegure su derecho de defensa y contradicci&oacute;n, incluyendo la opci&oacute;n de impugnar las decisiones que resulten contrarias a sus intereses. A lo anterior se suma la (v) garant&iacute;a de la presunci&oacute;n de inocencia, lo que conlleva que la responsabilidad del administrado se defina con base en hechos probados imputables al mismo, quedando proscrita la imposici&oacute;n de sanciones de plano amparadas s&oacute;lo en la ocurrencia objetiva de una falta o contravenci&oacute;n. Siendo ello as&iacute;, no es posible que se sancione al administrado, si previamente no se le ha garantizado un debido proceso, y se ha establecido plenamente su culpabilidad en la comisi&oacute;n de la falta o contravenci&oacute;n.</em>&rdquo;</p>
            <p style="text-align:justify">&ldquo;<em>Atendiendo a los cargos de la demanda, le correspondi&oacute; a la Corte establecer si con la notificaci&oacute;n era posible atribuirle al propietario del veh&iacute;culo, directamente y en cualquier caso, la responsabilidad por infracciones de tr&aacute;nsito. &nbsp;<u>Al respecto, sostuvo la Corporaci&oacute;n que el prop&oacute;sito de la notificaci&oacute;n debe ser el de permitirle al due&ntilde;o del veh&iacute;culo concurrir al proceso y tomar las medidas pertinentes para aclarar su situaci&oacute;n, no siendo posible atribuirle a &eacute;ste alg&uacute;n tipo de responsabilidad directa, a pesar de no haber tenido participaci&oacute;n en la infracci&oacute;n.</u></em>&rdquo; Subrayado del despacho.</p>
            <p>Que la Ley 1843 de 2017 en su art&iacute;culo 9 establece:</p>
            <p>&ldquo;<em>Normas complementarias. En lo que respecta a las dem&aacute;s actuaciones que se surten en el procedimiento administrativo sancionatorio, se regir&aacute; por las disposiciones del C&oacute;digo Nacional de Tr&aacute;nsito y en lo no regulado por esta, a lo dispuesto en el C&oacute;digo de Procedimiento Administrativo y de lo Contencioso Administrativo.</em>&rdquo;</p>
            <p>En coherencia con las citadas normas y de acuerdo con los antecedentes jurisprudenciales, se procede a declarar formalmente vinculado al presente proceso contravencional al locatario y/o conductor del veh&iacute;culo de placa ' . $rownot['placa'] . ', al cual se le enviar&aacute; citaci&oacute;n de notificaci&oacute;n, a fin de que comparezca a la diligencia de notificaci&oacute;n personal de la infracci&oacute;n de tr&aacute;nsito, motivo por el cual este despacho,</p>
            <h3 style="text-align:center">RESUELVE</h3>
            <p style="text-align:justify"><strong>ART&Iacute;CULO PRIMERO:</strong> Vincular al proceso contravencional al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . ', en calidad de locatario y/o conductor del veh&iacute;culo de placa ' . trim($rownot['placa']) . ', e iniciar investigaci&oacute;n contravencional en su contra, en atenci&oacute;n al considerando del presente auto.</p>
            <p style="text-align:justify"><strong>ART&Iacute;CULO SEGUNDO:</strong> ';
        if ($presente) {
            $html .= '<span>Notificar por medio del presente auto la infracci&oacute;n de tr&aacute;nsito con ocasi&oacute;n a la orden de comparendo No. ' . $ncomparendo . ' de fecha ' . $fecha . ', al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . '.</span></p>';
        } else {
            $html .= '<span>Env&iacute;ese citaci&oacute;n de notificaci&oacute;n personal al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . ' en calidad de locatario y/o conductor del veh&iacute;culo de placa ' . trim($rownot['placa']) . ', a la direcci&oacute;n reportada por el propietario. En caso de desconocer la informaci&oacute;n sobre el interesado, publicar la citaci&oacute;n se&ntilde;alada en el art&iacute;culo precedente, en la p&aacute;gina electr&oacute;nica o en un lugar de acceso al p&uacute;blico de la respectiva entidad por el t&eacute;rmino de cinco (5) d&iacute;as.</span></p>
            <p style="text-align:justify"><span>Si no pudiere hacerse notificaci&oacute;n personal al cabo de los cinco (5) d&iacute;as del env&iacute;o de la citaci&oacute;n, esta se har&aacute; por medio de aviso que se remitir&aacute; a la direcci&oacute;n, al n&uacute;mero de fax o al correo electr&oacute;nico que figuren en el expediente o puedan obtenerse del registro mercantil, acompa&ntilde;ado de copia &iacute;ntegra del acto administrativo. En caso de desconocer la informaci&oacute;n sobre el destinatario, el aviso, con copia &iacute;ntegra del acto administrativo, se publicar&aacute; en la p&aacute;gina electr&oacute;nica y en todo caso en un lugar de acceso al p&uacute;blico de la respectiva entidad por el t&eacute;rmino de cinco (5) d&iacute;as, con la advertencia de que la notificaci&oacute;n se considerar&aacute; surtida al finalizar el d&iacute;a siguiente al retiro del aviso.</span></p>';
        }
        $html .= '<p style="text-align:justify"><strong>ART&Iacute;CULO TERCERO:</strong> ';
        if ($presente) {
            $html .= '<span>Conceder, a partir de la fecha de notificiaci&oacute;n del presente auto, los descuentos de ley a los que haya lugar al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . '.</span></p>';
        } else {
            $html .= '<span>Conceder los descuentos de ley a los que haya lugar al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . '; a partir de la fecha en que le sea notificado el comparendo No. ' . $ncomparendo . '</span></p>';
        }
        $html .= '<p style="text-align:justify"><strong>ART&Iacute;CULO CUARTO:</strong> ';
        if ($presente) {
            $html .= '<span> Desvincular del proceso contravencional al se&ntilde;or (a) ' . $nombre . ' identificado con ' . $row_ciud['tipoident'] . ' No. ' . trim($row_ciud['ident']) . ', a partir de la notificaci&oacute;n del presente auto.</span></p>';
        } else {
            $html .= '<span>El se&ntilde;or(a) ' . $nombre . ' identificado con ' . $row_ciud['tipoident'] . ' No. ' . trim($row_ciud['ident']) . ', en su condici&oacute;n de propietario del veh&iacute;culo de placa ' . $rownot['placa'] . ', quedar&aacute; desvinculado del proceso contravencional hasta tanto se haya surtido la notificaci&oacute;n del comparendo No. ' . $ncomparendo . ', al se&ntilde;or(a) ' . $nnombre . ' identificado con ' . $row_ciud['ntipoident'] . ' No. ' . trim($row_ciud['nident']) . '.</span></p>';
        }
        $html .= '<p style="text-align:justify"><strong>ART&Iacute;CULO QUINTO:</strong> Contra la presente decisi&oacute;n procede recurso de reposici&oacute;n, conforme el art&iacute;culo 142 de la Ley 769 de 2002.</p>
            <h3 style="text-align:center">NOTIF&Iacute;QUESE Y C&Uacute;MPLASE</h3>
            <p>En este estado de la diligencia el despacho deja constancia que el presente auto se notifica en estrados, y se encuentra ejecutoriado por no haberse interpuesto recurso alguno, conforme el art&iacute;culo 139 de la Ley 769 de 2002</p>
            <p>Dada en ' . $municipio . ', a los ' . $fecha_base . '.</p>
            <div align="center">' . $firma . '</div>
            <h4 align="center">' . $funcionario . '</h4>
            <p>&nbsp;</p>
            <p align="left">
                <table width="100%" border="0"  valign="bottom">
                    <tr>
                        <td align="left" width="50%">
                            <h4>' . $nombre . '<br/>
                            CC O NIT: ' . $row_ciud['ident'] . '<br/>
                            PROPIETARIO</h4>
                        </td>
                        <td align="left" width="50%">
                            <h4>' . $nnombre . '<br/>
                            CC: ' . $row_ciud['nident'] . '<br/>
                            CONDUCTOR</h4>
                        </td>
                    </tr>
                </table>
             </p>
		</body>';
        $mpdf->WriteHTML($html);
        $mpdf->Output('AUTO No. ' . $rownot['nauto'] . '.pdf', 'I');
    } else {
        echo "Archivo no pudo ser generado, no hay informacion";
    }
} else {
    echo "Archivo no encontrado, no hay informacion para mostrar.";
}
?>