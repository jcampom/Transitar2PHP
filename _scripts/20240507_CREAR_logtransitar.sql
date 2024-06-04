USE [u859387114_transitar]
GO

/****** Object:  Table [dbo].[logTransitar]    Script Date: 07/05/2024 20:19:49 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[logTransitar](
	[id] [int] IDENTITY(457,1) NOT NULL,
	[origen] [varchar](250) NOT NULL,
	[texto] [varchar](8000) NOT NULL,
	[fecha] [datetime] NOT NULL,
PRIMARY KEY CLUSTERED 
(
	[id] ASC
)WITH (PAD_INDEX = OFF, STATISTICS_NORECOMPUTE = OFF, IGNORE_DUP_KEY = OFF, ALLOW_ROW_LOCKS = ON, ALLOW_PAGE_LOCKS = ON) ON [PRIMARY]
) ON [PRIMARY]
GO

ALTER TABLE [dbo].[logTransitar] ADD  DEFAULT (getdate()) FOR [fecha]
GO
