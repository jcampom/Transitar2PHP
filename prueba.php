CREATE VIEW VExportComp AS
SELECT
    Comp.Tcomparendos_ID AS idcomp,
    Comp.Tcomparendos_comparendo AS comp,
    NumComp(Comp.Tcomparendos_comparendo, Comp.Tcomparendos_origen) AS COMNUMERO,
    DATE_FORMAT(Comp.Tcomparendos_fecha, '%d/%m/%Y') AS COMFECHA,
    REPLACE(DATE_FORMAT(Comp.Tcomparendos_fecha, '%H:%i'), ':', '') AS COMHORA,
    Comp.Tcomparendos_lugar AS COMDIR,
    municipio.nombre AS COMDIVIPOMUNI,
    localidad.nombre AS COMDIVIPOLOCALIDADCOMUNA,
    Comp.Tcomparendos_placa AS COMPLACA,
    ISNULL(MunCiu.nombre, 0) AS COMDIVIPOMATRI,
    Comp.Tcomparendos_tipo AS COMTIPOVEHI,
    Serv.simit AS COMTIPOSER,
    ISNULL(Radi.simit, 1) AS COMCODIGORADIO,
    Moda.id_simit AS COMCODIGOMODALIDAD,
    Comp.Tcomparendos_tipopasajero AS COMCODIGOPASAJEROS,
    Comp.Tcomparendos_idinfractor AS COMINFRACTOR,
    Tide.simit AS COMTIPODOC,
    Infr.nombres AS COMNOMBRE,
    Infr.apellidos AS COMAPELLIDO,
    CASE WHEN Infr.fecha_nacimiento != '1900-01-01' THEN
        TIMESTAMPDIFF(YEAR, Infr.fecha_nacimiento, CURDATE())
    ELSE
        NULL
    END AS COMEDADINFRACTOR,
    Infr.direccion AS COMDIRINFRACTOR,
    Infr.email AS COMEMAIL,
    ISNULL(Infr.telefono, Infr.celular) AS COMTELEINFRACTOR,
    InfrCiu.nombre AS COMIDCIUDAD,
    CASE WHEN Tcomparendos_tipoinfractor = '4' THEN Infr.licencia_moto ELSE Infr.licencia_auto END AS COMLICENCIA,
    CASE WHEN Tcomparendos_tipoinfractor = '4' THEN Infr.categoria_licencia_moto ELSE Infr.categoria_licencia_auto END AS COMCATEGORIA,
    CASE WHEN Tcomparendos_tipoinfractor = '4' THEN Infr.organismo_licencia_moto ELSE Infr.organismo_licencia_auto END AS COMSECREEXPIDE,
    CASE WHEN Tcomparendos_tipoinfractor = '4' THEN DATE_FORMAT(Infr.vigencia_licencia_moto, '%d/%m/%Y') ELSE DATE_FORMAT(Infr.vigencia_licencia_auto, '%d/%m/%Y') END AS COMFECHAVENCE,
    Comp.Tcomparendos_tipoinfractor AS COMTIPOINFRAC,
    Comp.Tcomparendos_LT AS COMLICTRANSITO,
    Comp.Tcomparendos_OT AS COMDIVIPOLICEN,
    Comp.Tcomparendos_idprop AS COMIDENTIFICACION,
    TideP.simit AS COMIDTIPODOCPROP,
    (RTRIM(Prop.nombres) +  ' ' + RTRIM(Prop.apellidos)) AS COMNOMBREPROP,
    empresa.nombre AS COMNOMBREEMPRESA,
    empresa.Tterceros_identifica AS COMNITEMPRESA,
    Comp.Tcomparendos_TO AS COMTARJETAOPERACION,
    ISNULL(agente.Tterceros_placa, Comp.Tcomparendos_agente) AS COMPPLACAAGENTE,
    Comp.Tcomparendos_observaciones AS COMOBSERVA,
    CASE WHEN Tcomparendos_fuga > 0 THEN 'S' ELSE 'N' END AS COMFUGA,
    CASE WHEN Tcomparendos_accidente > 0 THEN 'S' ELSE 'N' END AS COMACCI,
    CASE WHEN Tcomparendos_patio > 0 THEN 'S' ELSE 'N' END AS COMINMOV,
    patio.nombre AS COMPATIOINMOVILIZA,
    patio.Tterceros_dir AS COMDIRPATIOINMOVI,
    '' AS COMGRUANUMERO,
    '' AS COMPLACAGRUA,
    0 AS COMCONSECUTIINMOVI,
    Comp.Tcomparendos_idtestigo AS COMIDENTIFICACIONTEST,
    (RTRIM(Test.nombres)+ ' ' + RTRIM(Test.apellidos)) AS COMNOMBRETESTI,
    REPLACE(Test.direccion, ',', ' ') AS COMDIRECRESTESTI,
    Test.telefono AS COMTELETESTIGO,
    ValorCompSMLV(Comp.Tcomparendos_ID) AS COMVALOR,
    (SELECT TOP 1 Tparameconomicos_vadicComp FROM parametros_economicos ) AS COMVALAD,
    (SELECT TOP 1 divipo FROM sedes) AS COMORGANISMO,
    Esta.simit AS COMESTADOCOM,
    CASE WHEN Tcomparendos_origen = '99999999' THEN 'S' ELSE 'N' END AS COMPOLCA,
    Comp.Tcomparendos_codinfraccion AS COMINFRACCION,
    ValorCompSMLV(Comp.Tcomparendos_ID) AS COMVALINFRA,
    Comp.Tcomparendos_gradoalcohol AS COMGRADOALCOHOL,
    CASE WHEN Tcomparendos_origen = '1' THEN 'S' ELSE 'N' END AS FOTOMULTA,
    DATE_FORMAT(ISNULL(TN.Tnotifica_notificaf, Comp.Tcomparendos_fecha), '%d/%m/%Y') AS FECHANOTIFICACION
FROM
    comparendos AS Comp
LEFT OUTER JOIN
    (SELECT DISTINCT Texportplano_comp FROM Texportplano WHERE Texportplano_tipo = '1') AS XP ON XP.Texportplano_comp = Comp.Tcomparendos_comparendo
INNER JOIN
    comparendos_codigos AS codigo ON Comp.Tcomparendos_codinfraccion = codigo.TTcomparendoscodigos_codigo

LEFT OUTER JOIN ciudadanos AS Infr ON CONVERT(Comp.Tcomparendos_idinfractor, SIGNED INTEGER) = Infr.numero_documento

LEFT OUTER JOIN
    ciudades AS InfrCiu ON Infr.ciudad_residencia = InfrCiu.id
LEFT OUTER JOIN
    ciudades AS MunCiu ON Comp.Tcomparendos_municioplaca = MunCiu.id
INNER JOIN
    tipo_identificacion AS Tide ON Tide.id = Infr.tipo_documento
LEFT OUTER JOIN
    Tnotifica AS TN ON Comp.Tcomparendos_ID = TN.Tnotifica_compid
INNER JOIN
    comparendos_estados AS Esta ON Comp.Tcomparendos_estado = Esta.id
LEFT OUTER JOIN
    ciudadanos AS Prop ON Comp.Tcomparendos_idprop = Prop.numero_documento
LEFT OUTER JOIN
    tipo_identificacion AS TideP ON TideP.id = Prop.tipo_documento
LEFT OUTER JOIN
    ciudadanos AS Test ON Comp.Tcomparendos_idtestigo = Test.numero_documento
LEFT OUTER JOIN
    tipo_servicio AS Serv ON Comp.Tcomparendos_servicio = Serv.id
LEFT OUTER JOIN
    vehiculos_modalidad AS Moda ON Comp.Tcomparendos_modalidad = Moda.id
LEFT OUTER JOIN
    vehiculos_radio AS Radi ON Comp.Tcomparendos_radio = Radi.id
LEFT OUTER JOIN
    terceros AS empresa ON Comp.Tcomparendos_empresa = empresa.id
LEFT OUTER JOIN
    terceros AS agente ON Comp.Tcomparendos_agente = agente.id
LEFT OUTER JOIN
    terceros AS patio ON Comp.Tcomparendos_patio = patio.id
LEFT OUTER JOIN
    terceros AS grua ON Comp.Tcomparendos_grua = grua.id
LEFT OUTER JOIN
    ciudades AS municipio ON Comp.Tcomparendos_municipiodir = municipio.id
LEFT OUTER JOIN
    ciudades AS localidad ON Comp.Tcomparendos_localidad = localidad.id
WHERE
    Comp.Tcomparendos_estado IN (1) AND XP.Texportplano_comp IS NULL;


