<?xml version='1.0' ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

	<xsl:template name="general_menu">
		<strong><i>Menu</i></strong><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php</xsl:text>
			</xsl:attribute>
			Home
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=listAllGame&amp;module=game</xsl:text>
			</xsl:attribute>
			Admin
		</a><br />
		<hr class="menuSep" />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?requestFilter=logIn</xsl:text>
			</xsl:attribute>
			Log In
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?requestFilter=logOut</xsl:text>
			</xsl:attribute>
			Log Out
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=userRegistration&amp;module=auth</xsl:text>
			</xsl:attribute>
			Register
		</a><br />
		<hr class="menuSep" />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=getPicksForUser&amp;module=pick</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='alt'>
				<xsl:text>Select picks for the next week's games</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='title'>
				<xsl:text>Select picks for the next week's games</xsl:text>
			</xsl:attribute>
			Picks
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=leaderboard&amp;module=ranking</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='alt'>
				<xsl:text>Results of games, weekly and overall standings.</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='title'>
				<xsl:text>Results of games, weekly and overall standings.</xsl:text>
			</xsl:attribute>
			Standings
		</a><br />
		<a>
			<xsl:attribute name='href'>
				<xsl:text>index.php?action=showHelp&amp;module=help</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='alt'>
				<xsl:text>Help and support (for the developer ... :) ).</xsl:text>
			</xsl:attribute>
			<xsl:attribute name='title'>
				<xsl:text>Help and support.(for the developer ... :) ))</xsl:text>
			</xsl:attribute>
			Help
		</a><br />
	</xsl:template>
	
</xsl:stylesheet>