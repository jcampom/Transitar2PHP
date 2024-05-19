USE [u859387114_transitar]
GO

-----------------------------------------
------- Object:  Table [dbo].[Tvehiculos]
-----------------------------------------
IF OBJECT_ID('dbo.Tvehiculos', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos](
	[Tvehiculos_ID] [int] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_placa] [varchar](10) NOT NULL,
	[Tvehiculos_fechaprop] [date] NULL,
	[Tvehiculos_identificacion] [nchar](20) NOT NULL,
	[Tvehiculos_identificacion2] [nchar](20) NULL,
	[Tvehiculos_tipocarroceria] [int] NOT NULL,
	[Tvehiculos_clase] [int] NOT NULL,
	[Tvehiculos_marca] [int] NOT NULL,
	[Tvehiculos_linea] [int] NOT NULL,
	[Tvehiculos_modelo] [int] NOT NULL,
	[Tvehiculos_color] [int] NOT NULL,
	[Tvehiculos_tiposervicio] [int] NOT NULL,
	[Tvehiculos_modalidad] [int] NOT NULL,
	[Tvehiculos_capacidadpasajeros] [tinyint] NOT NULL,
	[Tvehiculos_cilindraje] [int] NOT NULL,
	[Tvehiculos_chasis] [varchar](30) NOT NULL,
	[Tvehiculos_motor] [varchar](30) NOT NULL,
	[Tvehiculos_serie] [varchar](30) NULL,
	[Tvehiculos_VIN] [varchar](30) NULL,
	[Tvehiculos_puertas] [tinyint] NULL,
	[Tvehiculos_combustible] [tinyint] NOT NULL,
	[Tvehiculos_ejes] [tinyint] NULL,
	[Tvehiculos_peso] [int] NULL,
	[Tvehiculos_origen] [int] NOT NULL,
	[Tvehiculos_declaracion] [varchar](20) NULL,
	[Tvehiculos_fdeclaracion] [date] NULL,
	[Tvehiculos_paisorigen] [int] NULL,
	[Tvehiculos_potencia] [smallint] NULL,
	[Tvehiculos_clasificacion] [int] NOT NULL,
	[Tvehiculos_anofabricacion] [smallint] NULL,
	[Tvehiculos_transportador] [int] NULL,
	[Tvehiculos_actaimportacion] [varchar](20) NULL,
	[Tvehiculos_blindado] [int] NULL,
	[Tvehiculos_blindajonivel] [tinyint] NULL,
	[Tvehiculos_factura] [nchar](10) NOT NULL,
	[Tvehiculos_ffactura] [date] NULL,
	[Tvehiculos_pignorado] [int] NULL,
	[Tvehiculos_estado] [tinyint] NOT NULL,
	[Tvehiculos_acreedorp] [int] NULL,
	[Tvehiculos_verificacion] [varchar](12) NULL,
	[Tvehiculos_fecha] [datetime] NOT NULL,
	[Tvehiculos_liquidacion] [varchar](12) NULL,
	[Tvehiculos_SOAT] [varchar](20) NOT NULL,
	[Tvehiculos_SOATfecha] [date] NULL,
	[Tvehiculos_mecanica] [nchar](15) NULL,
	[Tvehiculos_mecanicafecha] [date] NULL,
	[Tvehiculos_LT] [varchar](20) NOT NULL,
	[Tvehiculos_sustrato] [bigint] NOT NULL,
	[Tvehiculos_cartaacepta] [varchar](12) NULL,
	[Tvehiculos_radio] [tinyint] NULL,
	[Tvehiculos_tipopasajero] [tinyint] NULL,
	[Tvehiculos_user] [varchar](20) NULL,
	[Tvehiculos_medidacautelar] [int] NULL,
	[Tvehiculos_chasisind] [tinyint] NULL,
	[Tvehiculos_ftc] [varchar](20) NULL,
	[Tvehiculos_ftch] [varchar](20) NULL,
	[Tvehiculos_ffc] [varchar](15) NULL,
	[Tvehiculos_carrocero] [int] NULL,
	[Tvehiculos_capacidadcarga] [int] NULL,
	[Tvehiculos_regrabmotor] [int] NULL,
	[Tvehiculos_regrabchasis] [int] NULL,
	[Tvehiculos_adaptadoense] [int] NULL,
	[Tvehiculos_polarizado] [int] NULL,
	[Tvehiculos_fabricante] [int] NULL,
	[Tvehiculos_observaciones] [varchar](max) NULL,
	[Tvehiculos_OT] [int] NULL,
	[Tvehiculos_TO] [varchar](15) NULL,
	[Tvehiculos_compa] [bit] NULL,
	[Tvehiculos_inmovilizado] [bit] NULL,
 CONSTRAINT [PK_Tvehiculos] PRIMARY KEY NONCLUSTERED 
(
	[Tvehiculos_placa] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON, FILLFACTOR = 90) ON [PRIMARY]
) ON [PRIMARY] TEXTIMAGE_ON [PRIMARY]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_fechaprop]  DEFAULT (NULL) FOR [Tvehiculos_fechaprop]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_serie]  DEFAULT ((0)) FOR [Tvehiculos_serie]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_VIN]  DEFAULT ((0)) FOR [Tvehiculos_VIN]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_fdeclaracion]  DEFAULT (NULL) FOR [Tvehiculos_fdeclaracion]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_blindado]  DEFAULT ((0)) FOR [Tvehiculos_blindado]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_ffactura]  DEFAULT (NULL) FOR [Tvehiculos_ffactura]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_acreedorp]  DEFAULT ((0)) FOR [Tvehiculos_acreedorp]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_fecha]  DEFAULT (NULL) FOR [Tvehiculos_fecha]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_chasisind]  DEFAULT ((0)) FOR [Tvehiculos_chasisind]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_capacidadcarga]  DEFAULT ((0)) FOR [Tvehiculos_capacidadcarga]
GO

ALTER TABLE [dbo].[Tvehiculos] ADD  CONSTRAINT [DF_Tvehiculos_Tvehiculos_regrabmotor]  DEFAULT ((0)) FOR [Tvehiculos_regrabmotor]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ID'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de placa' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_placa'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha de Propiedad' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_fechaprop'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. identificación del propietario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_identificacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Identificación Segundo propietario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_identificacion2'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de carrocería' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_tipocarroceria'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Clase del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_clase'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Marca del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_marca'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Línea del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_linea'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Modelo del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_modelo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Color del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_color'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de servicio del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_tiposervicio'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Modalidad del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_modalidad'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Capacidad de pasajeros' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_capacidadpasajeros'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Cilindraje del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_cilindraje'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. chasís' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_chasis'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Motor' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_motor'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. serie' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_serie'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. VIN' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_VIN'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de puertas' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_puertas'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de combustible' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_combustible'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de ejes' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ejes'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Peso del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_peso'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Origen del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_origen'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. declaración de importación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_declaracion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha de la declaración de importación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_fdeclaracion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'País de origen' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_paisorigen'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Potencia del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_potencia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Claificación del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_clasificacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Año de fabricación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_anofabricacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Empresa transportadora (solo servicio público)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_transportador'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. acta de importación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_actaimportacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Blindado? (Sí / No)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_blindado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Nivel de blindaje' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_blindajonivel'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de factura de compra del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_factura'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha de factura de compra del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ffactura'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Pignorado? (Sí / No)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pignorado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Estado del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_estado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Acreedor prendario (Sí / No)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_acreedorp'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Código de verificación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_verificacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha de ingreso al sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_fecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Número de liquidación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_liquidacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. SOAT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_SOAT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha vencimiento del SOAT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_SOATfecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. revisión tecnomecánica' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mecanica'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha vencimiento de revisión tecnomecánica' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mecanicafecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de licencia de tránsito' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_LT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de sustrato' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_sustrato'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de carta de aceptación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_cartaacepta'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Radio de acción del vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_radio'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo pasajeros ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_tipopasajero'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Usuario del sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_user'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Medida cautelar (SI / NO)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_medidacautelar'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Chasís independiente (SI / NO)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_chasisind'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Ficha Técnica de Carrocería ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ftc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Ficha Técnica Chasis' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ftch'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Factura fabricación Carrocería' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ffc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Carrocero Fabricante ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_carrocero'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Capacidad de carga' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_capacidadcarga'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Regrabación de motor (id tramite)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_regrabmotor'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Regrabación de chasis (id tramite)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_regrabchasis'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Adaptado a enseñanza (id tramite)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_adaptadoense'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Polarizado (id tramite)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_polarizado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fabicante vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_fabricante'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Observaciones' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_observaciones'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Organismo de Tránsito que expide la licencia de transito' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_OT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. de tarjeta de operación (servicio público)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_TO'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Ingresado por comparendo (No Matriculado)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_compa'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tvehiculos_inmovilizado (Inmovilizado por comparendo)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos', @level2type=N'COLUMN',@level2name=N'Tvehiculos_inmovilizado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tabla que almacena la informacion de los vehículos. ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos'
GO

----------------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_carroceros]
----------------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_carroceros', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_carroceros;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_carroceros](
	[Tcarroceros_ID] [float] NULL,
	[Tcarroceros_inscripcion] [nvarchar](255) NULL,
	[Tcarroceros_nombre] [nvarchar](255) NULL,
	[Tcarroceros_representante] [nvarchar](255) NULL,
	[Tcarroceros_identificacion] [float] NULL,
	[Tcarroceros_direccion] [nvarchar](255) NULL,
	[Tcarroceros_telefono] [float] NULL,
	[Tcarroceros_ciudad] [nvarchar](255) NULL
) ON [PRIMARY]
GO


------------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_ccolor]
------------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_ccolor', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_ccolor;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_ccolor](
	[Tvehiculos_ccolor_ID] [int] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_ccolor_liquidacion] [bigint] NOT NULL,
	[Tvehiculos_ccolor_placa] [varchar](10) NOT NULL,
	[Tvehiculos_ccolor_verificacion] [varchar](12) NOT NULL,
	[Tvehiculos_ccolor_cactual] [int] NULL,
	[Tvehiculos_ccolor_cnuevo] [int] NULL,
	[Tvehiculos_ccolor_LTActual] [varchar](20) NULL,
	[Tvehiculos_ccolor_LTdenuncia] [varchar](20) NULL,
	[Tvehiculos_ccolor_fechadenuncia] [datetime] NULL,
	[Tvehiculos_ccolor_LTnueva] [varchar](20) NULL,
	[Tvehiculos_ccolor_sustrato] [varchar](20) NULL,
	[Tvehiculos_ccolor_user] [varchar](20) NULL,
	[Tvehiculos_ccolor_fecha] [datetime] NULL,
	[Tvehiculos_ccolor_fechaRUNT] [date] NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Número de liquidación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_liquidacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Placa' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_placa'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Número de verificación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_verificacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Color Actual' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_cactual'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Color Nuevo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_cnuevo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Licencia Tránsito (Actual)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_LTActual'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Denuncia (Pérdida)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_LTdenuncia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha Denuncia (Pérdida)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_fechadenuncia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del tramite ante RUNT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor', @level2type=N'COLUMN',@level2name=N'Tvehiculos_ccolor_fechaRUNT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tramite de cambio de color' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_ccolor'
GO


--------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_CM]
--------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_CM', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_CM;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_CM](
	[Tvehiculos_CM_ID] [int] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_CM_liquidacion] [bigint] NOT NULL,
	[Tvehiculos_CM_ddt] [varchar](20) NULL,
	[Tvehiculos_CM_ddtf] [date] NULL,
	[Tvehiculos_CM_auto_ddt] [int] NULL,
	[Tvehiculos_CM_verificacion] [varchar](12) NOT NULL,
	[Tvehiculos_CM_tipo] [tinyint] NOT NULL,
	[Tvehiculos_CM_placa] [varchar](10) NOT NULL,
	[Tvehiculos_CM_docdesc] [varchar](20) NULL,
	[Tvehiculos_CM_docdescf] [date] NULL,
	[Tvehiculos_CM_auto_docdesc] [int] NULL,
	[Tvehiculos_CM_docnorec] [varchar](20) NULL,
	[Tvehiculos_CM_docnorecf] [date] NULL,
	[Tvehiculos_CM_auto_docnorec] [int] NULL,
	[Tvehiculos_CM_resolucion] [varchar](20) NULL,
	[Tvehiculos_CM_resolucionf] [date] NULL,
	[Tvehiculos_CM_auto_resolucion] [int] NULL,
	[Tvehiculos_CM_fecha] [datetime] NOT NULL,
	[Tvehiculos_CM_user] [varchar](20) NOT NULL,
	[Tvehiculos_CM_LTActual] [varchar](20) NULL,
	[Tvehiculos_CM_LTdenuncia] [varchar](12) NULL,
	[Tvehiculos_CM_fechadenuncia] [date] NULL,
	[Tvehiculos_CM_docacc] [varchar](20) NULL,
	[Tvehiculos_CM_docaccf] [date] NULL,
	[Tvehiculos_CM_docdet] [varchar](20) NULL,
	[Tvehiculos_CM_docdetf] [date] NULL,
	[Tvehiculos_CM_nrespdf] [varchar](10) NULL,
	[Tvehiculos_CM_fechaRUNT] [date] NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero de liquidacion' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_liquidacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Documento destrucción total' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_ddt'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Documento destrucción total fecha' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_ddtf'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Autoridad documento destrucción total' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_auto_ddt'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo cancelación matrícula' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_tipo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Placa' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_placa'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'# de Documento que Certifica desconocimiento del paradero del Vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docdesc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del Documento que Certifica desconocimiento ' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docdescf'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Autoridad que Expide Documento' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_auto_docdesc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'# de Documento sobre la NO Recuperación del Vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docnorec'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha # de Documento sobre la NO Recuperación del Vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docnorecf'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Autoridad que expide doc sobre la NO Recuperación del Vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_auto_docnorec'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Resolución que cancela la Tarjeta de Operación  expedida por la Autoridad de Transporte' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_resolucion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha resolución' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_resolucionf'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_fecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Licencia de transito (Actual)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_LTActual'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Denuncia (Pérdida)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_LTdenuncia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'fecha Denuncia (Pérdida)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_fechadenuncia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Doc. Certificado Accidente (Informe accidente)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docacc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha doc. cert. accidente (informe accidente)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docaccf'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Doc. detalle vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docdet'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha doc. detalle vehículo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_docdetf'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del tramite ante RUNT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_fechaRUNT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Informacion de Cancelacion de Matricula por Hurto' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM'
GO


-------------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_CM_tipo]
-------------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_CM_tipo', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_CM_tipo;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_CM_tipo](
	[Tvehiculos_CM_tipo_ID] [tinyint] NOT NULL,
	[Tvehiculos_CM_tipo_tipo] [nvarchar](150) NOT NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM_tipo', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_tipo_ID'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo cancelación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM_tipo', @level2type=N'COLUMN',@level2name=N'Tvehiculos_CM_tipo_tipo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Cancelación de matrícula tipos' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_CM_tipo'
GO


------------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_estado]
------------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_estado', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_estado;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_estado](
	[Tvehiculos_estado_ID] [tinyint] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_estado_nombre] [nchar](20) NOT NULL
) ON [PRIMARY]
GO

--------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_mc]
--------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_mc', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_mc;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_mc](
	[Tvehiculos_mc_ID] [int] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_mc_placa] [varchar](8) NOT NULL,
	[Tvehiculos_mc_departamento] [int] NOT NULL,
	[Tvehiculos_mc_municipio] [int] NOT NULL,
	[Tvehiculos_mc_tipoid] [tinyint] NULL,
	[Tvehiculos_mc_ident] [varchar](50) NULL,
	[Tvehiculos_mc_dnombre] [varchar](50) NOT NULL,
	[Tvehiculos_mc_oj] [varchar](20) NOT NULL,
	[Tvehiculos_mc_foj] [date] NOT NULL,
	[Tvehiculos_mc_entidad] [int] NOT NULL,
	[Tvehiculos_mc_tlimitacion] [int] NOT NULL,
	[Tvehiculos_mc_tproceso] [int] NOT NULL,
	[Tvehiculos_mc_verificacion] [varchar](20) NOT NULL,
	[Tvehiculos_mc_identpropietario] [varchar](50) NULL,
	[Tvehiculos_mc_numero] [varchar](20) NULL,
	[Tvehiculos_mc_tipomc] [tinyint] NULL,
	[Tvehiculos_mc_levantar] [int] NULL,
	[Tvehiculos_mc_destino] [varchar](50) NULL,
	[Tvehiculos_mc_cargo] [varchar](50) NULL,
	[Tvehiculos_mc_lugar] [varchar](50) NULL,
	[Tvehiculos_mc_direccion] [varchar](50) NULL,
	[Tvehiculos_mc_ciudad] [varchar](20) NULL,
	[Tvehiculos_mc_user] [varchar](20) NOT NULL,
	[Tvehiculos_mc_fecha] [datetime] NOT NULL,
	[Tvehiculos_mc_fechaRUNT] [date] NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Medida cautelar ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_ID'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Departamento donde se expidio la medida' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_departamento'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Municipio donde se expidio la medida' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_municipio'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo identificación demandante' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_tipoid'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Identificación demandante' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_ident'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Demandante nombre' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_dnombre'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Número de order Judicial' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_oj'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha orden judicial' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_foj'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Entidad que expide la orden' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_entidad'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de limitación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_tlimitacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de proceso' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_tproceso'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Identificacion propietario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_identpropietario'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero de Comunidado Externo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_numero'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo Medida Cautelar (Inscripcion o levantamiento)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_tipomc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo Medida Cautelar (Inscripcion o levantamiento' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_levantar'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Destinatario del documento' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_destino'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Cargo del destinatario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_cargo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Entidad del destinatario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_lugar'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Direccion de la entidad del destinatario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_direccion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Ciudad de la entidad del destinatario' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_ciudad'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del tramite ante RUNT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc', @level2type=N'COLUMN',@level2name=N'Tvehiculos_mc_fechaRUNT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Medida cautelar' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_mc'
GO

--------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_MI]
--------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_MI', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_MI;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_MI](
	[Tvehiculos_MI_ID] [int] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_MI_liquidacion] [varchar](12) NOT NULL,
	[Tvehiculos_MI_placa] [varchar](10) NOT NULL,
	[Tvehiculos_MI_verificacion] [varchar](12) NOT NULL,
	[Tvehiculos_MI_fecha] [datetime] NULL,
	[Tvehiculos_MI_user] [varchar](20) NULL,
	[Tvehiculos_MI_tiporeg] [tinyint] NULL,
	[Tvehiculos_MI_organismo] [int] NULL,
	[Tvehiculos_MI_acto] [varchar](15) NULL,
	[Tvehiculos_MI_facto] [date] NULL,
	[Tvehiculos_MI_placa1] [nchar](10) NULL,
	[Tvehiculos_MI_poliza] [varchar](20) NULL,
	[Tvehiculos_MI_fpoliza] [date] NULL,
	[Tvehiculos_MI_certificado] [varchar](15) NULL,
	[Tvehiculos_MI_fcertificado] [date] NULL,
	[Tvehiculos_MI_LT] [varchar](20) NOT NULL,
	[Tvehiculos_MI_sustrato] [bigint] NOT NULL,
	[Tvehiculos_MI_fechaRUNT] [date] NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_ID'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Número de liquidación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_liquidacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Placa' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_placa'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Número de verificación' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_verificacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_fecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'usuario del sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_user'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Tipo de registro (Reposición - caución)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_tiporeg'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Organísmo de tráncito que cancela la matricula (reposición)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_organismo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'# Acto Administrativo de Cancelación Matrícula (Si es por Reposición)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_acto'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha Acto Administrativo Cancelación de Matrícula (Si es por Reposición)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_facto'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Placa Vehículo que Cancela Matrícula (Si es por Reposición)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_placa1'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'# de póliza (Si es por Caución)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_poliza'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha de Póliza (Si es por Caución)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_fpoliza'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'# Certificado de Cumplimiento de Requisitos (Ambos Casos)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_certificado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha Certificado de Cumplimiento de Requisito (Ambos Casos)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_fcertificado'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Licencia de transito' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_LT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Sustrato' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI', @level2type=N'COLUMN',@level2name=N'Tvehiculos_MI_sustrato'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Información de Matricula Inicial' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_MI'
GO


-------------------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_pasajerostipo]
-------------------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_pasajerostipo', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_pasajerostipo;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_pasajerostipo](
	[Tvehiculos_pasajerostipo_ID] [tinyint] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_pasajerostipo_tipo] [varchar](20) NOT NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'ID' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pasajerostipo', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pasajerostipo_ID'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Transporte pasajeros tipo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pasajerostipo', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pasajerostipo_tipo'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Transporte de Pasajeros  Tipo' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pasajerostipo'
GO


---------------------------------------------
------- Object:  Table [dbo].[Tvehiculos_pig]
---------------------------------------------
IF OBJECT_ID('dbo.Tvehiculos_pig', 'U') IS NOT NULL 
  DROP TABLE dbo.Tvehiculos_pig;

SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Tvehiculos_pig](
	[Tvehiculos_pig_ID] [int] IDENTITY(1,1) NOT NULL,
	[Tvehiculos_pig_liquidacion] [bigint] NOT NULL,
	[Tvehiculos_pig_doc] [varchar](50) NOT NULL,
	[Tvehiculos_pig_entidad] [int] NOT NULL,
	[Tvehiculos_pig_placa] [varchar](10) NOT NULL,
	[Tvehiculos_pig_verificacion] [varchar](12) NOT NULL,
	[Tvehiculos_pig_user] [varchar](20) NOT NULL,
	[Tvehiculos_pig_fecha] [datetime] NOT NULL,
	[Tvehiculos_pig_LTActual] [bigint] NULL,
	[Tvehiculos_pig_LTdenuncia] [varchar](12) NULL,
	[Tvehiculos_pig_fechadenuncia] [date] NULL,
	[Tvehiculos_pig_LTnueva] [bigint] NULL,
	[Tvehiculos_pig_obs] [varchar](255) NULL,
	[Tvehiculos_pig_sustrato] [bigint] NULL,
	[Tvehiculos_pig_fechaRUNT] [date] NULL
) ON [PRIMARY]
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero de liquidacion' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_liquidacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. doc de pignoración' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_doc'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Entidad pignoradora' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_entidad'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero del placa' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_placa'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Numero de verificacion' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_verificacion'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Usuario que registró' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_user'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del sistema' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_fecha'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Licencia de transito (Actual)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_LTActual'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Denuncia (perdida)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_LTdenuncia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha Denuncia (perdida)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_fechadenuncia'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. Licencia de transito (nueva)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_LTnueva'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Observacion' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_obs'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'No. sustrato (nueva licencia)' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_sustrato'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Fecha del tramite ante RUNT' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig', @level2type=N'COLUMN',@level2name=N'Tvehiculos_pig_fechaRUNT'
GO

EXEC sys.sp_addextendedproperty @name=N'MS_Description', @value=N'Contiene la información detallada de las pignoraciones.' , @level0type=N'SCHEMA',@level0name=N'dbo', @level1type=N'TABLE',@level1name=N'Tvehiculos_pig'
GO

