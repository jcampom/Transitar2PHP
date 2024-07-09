USE [u859387114_transitar]
GO

/****** Object:  Table [dbo].[detalle_conceptos_liquidaciones]    Script Date: 09/07/2024 16:54:22 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

IF (OBJECT_ID('dbo.detalle_liquidaciones') IS NOT NULL)
BEGIN
    --PRINT 'Exists'
	DROP TABLE  [dbo].[detalle_conceptos_liquidaciones];
END

CREATE TABLE [dbo].[detalle_conceptos_liquidaciones](
	[id] [int] IDENTITY(1040,1) NOT NULL,
	[liquidacion] varchar(12)	null,
	[tramite] [int] NOT NULL,
	[concepto] [int] NOT NULL,
	[valor] float	not null,
	[mora] [int] default 0 NULL,
	[comparendo] [int] default 0 NULL,
	[dt] [int] default 0 NULL,
	[cuota] [int] default 0 NULL,
	[terceros] [int] NOT NULL,
	[estado] [int] default 0 NOT NULL,
	[honorario] [int] default 0 NULL,
	[cobranza] [int] default 0 NULL,
 CONSTRAINT [PK_detalle_conceptos_liquidaciones_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'u859387114_transitar.detalle_conceptos_liquidaciones' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'detalle_conceptos_liquidaciones'
GO


------------------------------------------------------------
PRINT 'INSERT:detalle_conceptos_liquidaciones'
truncate table u859387114_transitar..detalle_conceptos_liquidaciones;
SET IDENTITY_INSERT u859387114_transitar..detalle_conceptos_liquidaciones ON
INSERT INTO u859387114_transitar..detalle_conceptos_liquidaciones( id ,liquidacion ,tramite ,concepto ,valor ,mora ,comparendo ,dt ,cuota ,terceros ,estado ,honorario ,cobranza )
select Tliqconcept_ID as id, Tliqconcept_liq as liquidacion, Tliqconcept_tramite as tramite, ISNULL(Tliqconcept_conceptoID,0) as concepto, Tliqconcept_valor as valor, 0 as mora, 0 as comparendo, 0 as dt, 0 as cuota, Tliqconcept_terceros as terceros, t2.Tliquidacionmain_estado as estado, 0 as honorario, 0 as cobranza from cienaga..Tliqconcept t1, cienaga..TliquidacionMain t2 where t1.Tliqconcept_liq = cast(t2.Tliquidacionmain_ID as varchar)
SET IDENTITY_INSERT u859387114_transitar..detalle_conceptos_liquidaciones OFF
------------------------------------------------------------

