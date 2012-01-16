<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:include href='../../../common/templates/includes/general_menu.xsl' />
<xsl:include href='../../../common/templates/includes/footer.xsl' />

<xsl:include href='../../../common/templates/includes/admin_menu.xsl' />

<xsl:template match="/">
	
	<html>
		<head>
			<title>Super14 Sweepstakes</title>
			<link href="modules/common/templates/styles.css" rel="stylesheet" type="text/css" />
		</head>
		<body>
			<h2>Super 14 - Users for  - <xsl:value-of select="page/group_name" /></h2>
			<hr />
			<span class='main'>Message: </span><span class='message'><xsl:value-of select='page/message' /></span>
			<table width='90%'>
				<tr>
					<td valign='top' width='15%'>
						<xsl:call-template name="general_menu" />
					</td>
					<td valign='top' width='15%'>
						<xsl:call-template name="admin_menu" />
					</td>
					<td valign='top'>
						<form>
							<xsl:attribute name="action">
								<xsl:text>index.php?action=updateGroupUser&amp;module=user&amp;id=</xsl:text>
								<xsl:value-of select="page/group_id" />
							</xsl:attribute>
							<xsl:attribute name="method">POST</xsl:attribute>
							<table>
								<xsl:apply-templates select="page/user" />
								<tr>
									<td><xsl:apply-templates select="page/submit" /></td>
								</tr>
							</table>
						</form>
					</td>
				</tr>
			</table>
			<xsl:call-template name='footer' />
		</body>
	</html>
	
</xsl:template>

<xsl:template match="user">

	<tr><td>
		<xsl:if test="checked='Y'">
			<input>
				<xsl:attribute name="name"><xsl:text>user[]</xsl:text></xsl:attribute>
				<xsl:attribute name="type"><xsl:text>checkbox</xsl:text></xsl:attribute>
				<xsl:attribute name="checked"><xsl:text>CHECKED</xsl:text></xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
			</input>
		</xsl:if>
		<xsl:if test="not(checked='Y')">
			<input>
				<xsl:attribute name="name"><xsl:text>user[]</xsl:text></xsl:attribute>
				<xsl:attribute name="type"><xsl:text>checkbox</xsl:text></xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
			</input>
		</xsl:if>
		<xsl:value-of select="name" />
	</td></tr>
	
</xsl:template>

<xsl:template match="submit">
	<input>
		<xsl:attribute name="type">SUBMIT</xsl:attribute>
		<xsl:attribute name="name"><xsl:value-of select="name" /></xsl:attribute>
		<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
	</input>
</xsl:template>
</xsl:stylesheet>