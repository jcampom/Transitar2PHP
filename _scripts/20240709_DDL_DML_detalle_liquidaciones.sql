USE [u859387114_transitar]
GO

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

IF (OBJECT_ID('dbo.detalle_liquidaciones') IS NOT NULL)
BEGIN
    --PRINT 'Exists'
	DROP TABLE  [dbo].[detalle_liquidaciones];
END

CREATE TABLE [dbo].[detalle_liquidaciones](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[liquidacion] [int] NOT NULL,
	[tramite] [int] NOT NULL,
	[comparendo] [int]  NULL,
	[dt] [int]  NULL,
	[acuerdo] [nvarchar](11)  NULL,
	[cuota] [int]  NULL,
 CONSTRAINT [PK_detalle_liquidaciones_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'u859387114_transitar.detalle_liquidaciones' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'detalle_liquidaciones'
GO

------------------------------------------------------------
PRINT 'INSERT:detalle_liquidaciones'
truncate table u859387114_transitar..detalle_liquidaciones;
SET IDENTITY_INSERT u859387114_transitar..detalle_liquidaciones ON
INSERT INTO u859387114_transitar..detalle_liquidaciones (id ,liquidacion ,tramite)
SELECT Tliquidaciontramites_ID as id, Tliquidaciontramites_liq as liquidacion, Tliquidaciontramites_tramite as tramite
FROM cienaga..Tliquidaciontramites;
SET IDENTITY_INSERT u859387114_transitar..detalle_liquidaciones OFF
------------------------------------------------------------