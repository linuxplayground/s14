<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="admin_menu">
		<strong><i>General</i></strong><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listUser&amp;module=user</xsl:text>
			</xsl:attribute>
			Users
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listGroup&amp;module=user</xsl:text>
			</xsl:attribute>
			Groups
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listPermission&amp;module=user</xsl:text>
			</xsl:attribute>
			Permissions
		</a><br/>
		<hr class="menuSep" />
		<strong><i>Modules</i></strong><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listNews&amp;module=news</xsl:text>
			</xsl:attribute>
			News
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listTeam&amp;module=team</xsl:text>
			</xsl:attribute>
			Teams
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listMargin&amp;module=margin</xsl:text>
			</xsl:attribute>
			Margins
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listAllGame&amp;module=game</xsl:text>
			</xsl:attribute>
			Games
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listMatchType&amp;module=matchType</xsl:text>
			</xsl:attribute>
			Match Types
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listScore&amp;module=score</xsl:text>
			</xsl:attribute>
			Score
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listBonus&amp;module=bonus</xsl:text>
			</xsl:attribute>
			Bonus Weeks
		</a><br />		
	</xsl:template>
	
</xsl:stylesheet>