USE u859387114_transitar
GO
-------------------------------------------------------------
update d
set d.comparendo = (
select  tc.Tliqconcept_doc 
from cienaga..Tliqconcept tc
where tc.Tliqconcept_tipodoc=4 --comparendos
and tc.Tliqconcept_ID = d.id
)
from u859387114_transitar..detalle_conceptos_liquidaciones d
-------------------------------------------------------------
update d
set d.dt = (
select  tc.Tliqconcept_doc 
from cienaga..Tliqconcept tc
where tc.Tliqconcept_tipodoc=6   --acuerdos
and tc.Tliqconcept_ID = d.id
)
from u859387114_transitar..detalle_conceptos_liquidaciones d
-------------------------------------------------------------
update d
set d.cuota = (
select  tc.Tliqconcept_doc 
from cienaga..Tliqconcept tc
where tc.Tliqconcept_tipodoc =7 --derechos de tránsito
and tc.Tliqconcept_ID = d.id
)
from u859387114_transitar..detalle_conceptos_liquidaciones d
------------------------------------------------------------