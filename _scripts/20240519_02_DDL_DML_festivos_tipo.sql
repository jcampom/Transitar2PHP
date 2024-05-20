USE [u859387114_transitar]
GO

---------------------------------------------
------- Object:  Table [dbo].[festivos_tipo]
---------------------------------------------
IF OBJECT_ID('dbo.festivos_tipo', 'U') IS NOT NULL 
  DROP TABLE dbo.festivos_tipo;
  
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[festivos_tipo](
	[Tfestivostipo_id] [int] NULL,
	[Tfestivostipo_nombre] [varchar](50) NULL
) ON [PRIMARY]
GO

----------------------------------------
--- Llenado tabla [dbo].[tfestivos_tipo]
----------------------------------------
TRUNCATE TABLE [u859387114_transitar].[dbo].[festivos_tipo];
--SET IDENTITY_INSERT [u859387114_transitar].[dbo].[festivos_tipo] ON;
INSERT INTO [u859387114_transitar].[dbo].[festivos_tipo] (Tfestivostipo_id,Tfestivostipo_nombre ) 
SELECT Tfestivostipo_id,Tfestivostipo_nombre
FROM [cienaga].[dbo].[tfestivos_tipo];
--SET IDENTITY_INSERT [u859387114_transitar].[dbo].[festivos_tipo] OFF;
---------------------------------------------------------------------------------------------------------
