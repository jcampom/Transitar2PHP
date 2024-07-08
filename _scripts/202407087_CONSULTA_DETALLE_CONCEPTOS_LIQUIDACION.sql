--4	Comparendos         
--6	Acuerdos pago       
--7	Derechos de transito
insert into db.detalle_conceptos_liquidaciones (
liquidacion
,tramite
,concepto
,valor
,mora
,comparendo
,dt
,cuota
,terceros
,estado
,honorario
,cobranza
)
select 
t1.Tliqconcept_tipodoc as liquidacion,
t1.Tliqconcept_tramite as tramite,
ISNULL(t1.Tliqconcept_conceptoID,-1) as concepto,
t1.Tliqconcept_valor as valor,
0 as mora,
0 as  comparendo,
(case when t1.Tliqconcept_tipodoc=7 then t1.Tliqconcept_doc else 0 end) as  dt,
0 AS cuota,
T1.Tliqconcept_terceros AS terceros,
1 AS estado,
0 as honorario,
0 as cobranza
from CIENAGA..tliqconcept t1
WHERE 1=1
and t1.Tliqconcept_tipodoc not in (4,6)
-----
UNION 
select 
t1.Tliqconcept_tipodoc as liquidacion,
t1.Tliqconcept_tramite as tramite,
ISNULL(t1.Tliqconcept_conceptoID,-1) as concepto,
t1.Tliqconcept_valor as valor,
0 as mora,
(case when t1.Tliqconcept_tipodoc=4 then t1.Tliqconcept_doc else 0 end) as  comparendo,
0 as  dt,
0 AS cuota,
T1.Tliqconcept_terceros AS terceros,
1 AS estado,
ISNULL(t3.Tcomparendos_honorarios,0) as honorario,
ISNULL(t3.Tcomparendos_cobranza,0) as cobranza
from CIENAGA..tliqconcept t1, cienaga..Tcomparendos t3
WHERE 1=1
and t1.Tliqconcept_tipodoc=4
and t3.Tcomparendos_ID = (case when t1.Tliqconcept_tipodoc=4 then t1.Tliqconcept_doc else -1 end)
and t3.Tcomparendos_ID > 0
-----
UNION
select 
t1.Tliqconcept_tipodoc as liquidacion,
t1.Tliqconcept_tramite as tramite,
ISNULL(t1.Tliqconcept_conceptoID,-1) as concepto,
t1.Tliqconcept_valor as valor,
0 as mora,
0 as comparendo,
0 as dt,
T3.cuota AS cuota,
T1.Tliqconcept_terceros AS terceros,
1 AS estado,
0 as honorario,
0 as cobranza
from CIENAGA..tliqconcept t1, VCuotaAcuerdo t3
WHERE 1=1
and t1.Tliqconcept_tipodoc=6
and t3.id_acuerdo = (case when t1.Tliqconcept_tipodoc=6 then t1.Tliqconcept_doc else -1 end)
ORDER BY 1,2,3
