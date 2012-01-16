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
			<h2>Super 14 - Groups </h2>
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
						<xsl:apply-templates select="page/groups" />
						<br />
						<button>
							<xsl:attribute name='onClick'>
								<![CDATA[window.location='index.php?action=newGroup&module=user']]>
							</xsl:attribute>
							New
						</button>
					</td>
				</tr>
			</table>
			<xsl:call-template name='footer' />
		</body>
	</html>
	
</xsl:template>

<xsl:template match="page/groups">
	<table cellspacing='1'  bgcolor='#6699cc'>
		<tr>
			<th>Group Name</th>
			<th>Actions</th>
		</tr>
		<xsl:apply-templates select="group" />
	</table>
</xsl:template>

<xsl:template match="group">
	<tr>
		<td>
			<xsl:value-of select="name" />
		</td>
		<td>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=editGroup&module=user&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Edit
				</button>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=confirmDeleteGroup&module=user&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Delete
				</button>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=groupUsers&module=user&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Users
				</button>
				<button>
					<xsl:attribute name='onclick'>
						<![CDATA[window.location='index.php?action=groupPermissions&module=user&id=]]>
						<xsl:value-of select='id' />
						<![CDATA['; return false;]]>
					</xsl:attribute>
					Permissions
				</button>
		</td>
	</tr>
</xsl:template>

</xsl:stylesheet>