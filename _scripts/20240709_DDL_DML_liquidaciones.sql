USE [u859387114_transitar]
GO

/****** Object:  Table [dbo].[liquidaciones]    Script Date: 09/07/2024 15:00:03 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

IF (OBJECT_ID('dbo.liquidaciones') IS NOT NULL)
BEGIN
    --PRINT 'Exists'
	DROP TABLE  [dbo].[liquidaciones];
END

CREATE TABLE [dbo].[liquidaciones](
[id] [bigint] IDENTITY(1,1) NOT NULL,
tipo_tramite tinyint null,
ciudadano nvarchar(20) not null,
placa varchar(10) null,
nota_credito nchar(12) null,
tipo_servicio int default 0 not null,
clase_vehiculo int default 0 not null,
clasificacion_vehiculo int default 0 not null,
comparendo int default 0 not null,
estado tinyint default 1 not null,
usuario varchar(20) null,
fecha datetime not null,
fechayhora datetime not null,
empresa int not null,
 CONSTRAINT [PK_liquidaciones_id] PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_SSMA_SOURCE', @value=N'u859387114_transitar.liquidaciones' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'liquidaciones'
GO

------------------------------------------------------------
PRINT 'INSERT:liquidaciones'
truncate table u859387114_transitar..liquidaciones;
SET IDENTITY_INSERT u859387114_transitar..liquidaciones ON
INSERT INTO u859387114_transitar..liquidaciones (id ,tipo_tramite ,ciudadano ,placa ,nota_credito ,tipo_servicio ,clase_vehiculo ,clasificacion_vehiculo ,comparendo ,estado ,usuario ,fecha ,fechayhora ,empresa)
SELECT Tliquidacionmain_ID as id, Tliquidacionmain_tipodoc as tipo_tramite, Tliquidacionmain_idciudadano as ciudadano, Tliquidacionmain_placa as placa, Tliquidacionmain_nc as nota_credito, 0 as tipo_servicio, 0 as clase_vehiculo, 0 as clasificacion_vehiculo, 0 as comparendo, Tliquidacionmain_estado as estado, Tliquidacionmain_user as usuario, Tliquidacionmain_fecha as fecha, Tliquidacionmain_fecha as fechayhora, 1 as empresa
FROM cienaga..Tliquidacionmain;
SET IDENTITY_INSERT u859387114_transitar..acuerdosp_periodos OFF
------------------------------------------------------------