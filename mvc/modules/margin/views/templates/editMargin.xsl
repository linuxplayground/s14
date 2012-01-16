<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	
	<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
	<xsl:include href='../../../common/templates/includes/footer.xsl' />
	
	<xsl:include href='../../../common/templates/includes/admin_menu.xsl' />
	<xsl:include href='../../../common/templates/includes/form.xsl' />
	
	<xsl:template match='/'>
		<html>
			<head>
				<title>Super14 Sweepstakes</title>
				<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
			</head>
			<body>
				<h2>Super 14 - Margin</h2>
				<hr />
				<table width='90%'>
					<tr>
						<td valign='top' width="15%" >
							<xsl:call-template name='general_menu' />
						</td>
						<td valign='top' width="15%" >
							<xsl:call-template name='admin_menu' />
						</td>
						<td valign='top'>
							<xsl:apply-templates select='form' />
						</td>
					</tr>
				</table>
				<xsl:call-template name='footer' />
			</body>
		</html>
	</xsl:template>
	
</xsl:stylesheet>