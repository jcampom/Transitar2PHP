USE [u859387114_transitar]
GO
------------------------------------------------
------- Object:  Table [dbo].[Trecaudos_titulos]
------------------------------------------------
IF OBJECT_ID('dbo.Trecaudos_titulos', 'U') IS NOT NULL 
  DROP TABLE dbo.Trecaudos_titulos;
  
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[Trecaudos_titulos](
	[Trecaudos_titulos_ID] [bigint] IDENTITY(1,1) NOT NULL,
	[Trecaudos_liquidacion] [varchar](10) NOT NULL,
	[Trecaudos_titulos_num] [nvarchar](50) NOT NULL,
	[Trecaudos_titulos_fec] [date] NOT NULL,
	[Trecaudos_titulos_val] [float] NOT NULL,
 CONSTRAINT [pkTrecaudos_titulosID] PRIMARY KEY CLUSTERED 
(
	[Trecaudos_titulos_ID] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO
-------------------------------------------
--- Llenado tabla [dbo].[trecaudos_titulos]
-------------------------------------------
TRUNCATE TABLE [u859387114_transitar].[dbo].[trecaudos_titulos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[trecaudos_titulos] ON;
INSERT INTO [u859387114_transitar].[dbo].[trecaudos_titulos] (Trecaudos_titulos_ID ,Trecaudos_liquidacion ,Trecaudos_titulos_num ,Trecaudos_titulos_fec ,Trecaudos_titulos_val)
SELECT Trecaudos_titulos_ID ,Trecaudos_liquidacion ,Trecaudos_titulos_num ,Trecaudos_titulos_fec ,Trecaudos_titulos_val
FROM [cienaga].[dbo].[trecaudos_titulos];
SET IDENTITY_INSERT [u859387114_transitar].[dbo].[trecaudos_titulos] OFF;
---------------------------------------------------------------------------------------------------------------
