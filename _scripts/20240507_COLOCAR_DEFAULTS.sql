USE u8937114_transitar
GO
----------------------------------------------------------------------------------------------------------------------------------
ALTER TABLE detalle_liquidaciones ADD CONSTRAINT detalle_liquidaciones_DF_comparendo DEFAULT 0 FOR comparendo;
ALTER TABLE detalle_liquidaciones ADD CONSTRAINT detalle_liquidaciones_DF_dt DEFAULT 0 FOR dt;
ALTER TABLE detalle_liquidaciones ADD CONSTRAINT detalle_liquidaciones_DF_acuerdo DEFAULT 0 FOR acuerdo;
ALTER TABLE detalle_liquidaciones ADD CONSTRAINT detalle_liquidaciones_DF_cuota DEFAULT 0 FOR cuota;
----------------------------------------------------------------------------------------------------------------------------------
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_mora DEFAULT 0 FOR mora;
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_comparendo DEFAULT 0 FOR comparendo;
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_dt DEFAULT 0 FOR dt;
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_cuota DEFAULT 0 FOR cuota;
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_estado DEFAULT 1 FOR estado;
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_honorario DEFAULT 0 FOR honorario;
ALTER TABLE detalle_conceptos_liquidaciones ADD CONSTRAINT detalle_conceptos_liquidaciones_DF_cobranza DEFAULT 0 FOR cobranza;
----------------------------------------------------------------------------------------------------------------------------------
GO
