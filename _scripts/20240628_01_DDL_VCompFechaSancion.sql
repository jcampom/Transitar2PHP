USE u859387114_transitar
GO

/****** Object:  View [dbo].[VCompFechaSancion]    Script Date: 28/06/2024 16:58:22 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE VIEW [dbo].[VCompFechaSancion]
AS
SELECT C.Tcomparendos_ID AS id
	,C.Tcomparendos_comparendo AS comparendo
	,N.Tnotifica_notificaf AS fecha_notificacion
	,C.Tcomparendos_origen AS origen
	,dbo.DiasHabil(N.Tnotifica_notificaf, 6) AS dia6
	,dbo.DiasHabil(N.Tnotifica_notificaf, 31) AS dia31
FROM dbo.comparendos AS C
INNER JOIN dbo.Tnotifica AS N ON N.Tnotifica_comparendo = C.Tcomparendos_comparendo
WHERE (C.Tcomparendos_estado IN (1))
GO

EXEC sys.sp_addextendedproperty @name = N'MS_DiagramPane1'
	,@value = 
	N'[0E232FF0-B466-11cf-A24F-00AA00A3EFFF, 1.00]
Begin DesignProperties = 
   Begin PaneConfigurations = 
      Begin PaneConfiguration = 0
         NumPanes = 4
         Configuration = "(H (1[40] 4[20] 2[20] 3) )"
      End
      Begin PaneConfiguration = 1
         NumPanes = 3
         Configuration = "(H (1 [50] 4 [25] 3))"
      End
      Begin PaneConfiguration = 2
         NumPanes = 3
         Configuration = "(H (1 [50] 2 [25] 3))"
      End
      Begin PaneConfiguration = 3
         NumPanes = 3
         Configuration = "(H (4 [30] 2 [40] 3))"
      End
      Begin PaneConfiguration = 4
         NumPanes = 2
         Configuration = "(H (1 [56] 3))"
      End
      Begin PaneConfiguration = 5
         NumPanes = 2
         Configuration = "(H (2 [66] 3))"
      End
      Begin PaneConfiguration = 6
         NumPanes = 2
         Configuration = "(H (4 [50] 3))"
      End
      Begin PaneConfiguration = 7
         NumPanes = 1
         Configuration = "(V (3))"
      End
      Begin PaneConfiguration = 8
         NumPanes = 3
         Configuration = "(H (1[56] 4[18] 2) )"
      End
      Begin PaneConfiguration = 9
         NumPanes = 2
         Configuration = "(H (1 [75] 4))"
      End
      Begin PaneConfiguration = 10
         NumPanes = 2
         Configuration = "(H (1[66] 2) )"
      End
      Begin PaneConfiguration = 11
         NumPanes = 2
         Configuration = "(H (4 [60] 2))"
      End
      Begin PaneConfiguration = 12
         NumPanes = 1
         Configuration = "(H (1) )"
      End
      Begin PaneConfiguration = 13
         NumPanes = 1
         Configuration = "(V (4))"
      End
      Begin PaneConfiguration = 14
         NumPanes = 1
         Configuration = "(V (2))"
      End
      ActivePaneConfig = 0
   End
   Begin DiagramPane = 
      Begin Origin = 
         Top = 0
         Left = 0
      End
      Begin Tables = 
         Begin Table = "C"
            Begin Extent = 
               Top = 6
               Left = 38
               Bottom = 114
               Right = 263
            End
            DisplayFlags = 280
            TopColumn = 0
         End
         Begin Table = "N"
            Begin Extent = 
               Top = 6
               Left = 301
               Bottom = 114
               Right = 487
            End
            DisplayFlags = 280
            TopColumn = 0
         End
      End
   End
   Begin SQLPane = 
   End
   Begin DataPane = 
      Begin ParameterDefaults = ""
      End
      Begin ColumnWidths = 9
         Width = 284
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
         Width = 1500
      End
   End
   Begin CriteriaPane = 
      Begin ColumnWidths = 11
         Column = 1440
         Alias = 1530
         Table = 1170
         Output = 720
         Append = 1400
         NewValue = 1170
         SortType = 1350
         SortOrder = 1410
         GroupBy = 1350
         Filter = 6120
         Or = 6210
         Or = 1350
         Or = 1350
      End
   End
End
'
	,@level0type = N'SCHEMA'
	,@level0name = N'dbo'
	,@level1type = N'VIEW'
	,@level1name = N'VCompFechaSancion'
GO

EXEC sys.sp_addextendedproperty @name = N'MS_DiagramPaneCount'
	,@value = 1
	,@level0type = N'SCHEMA'
	,@level0name = N'dbo'
	,@level1type = N'VIEW'
	,@level1name = N'VCompFechaSancion'
GO


