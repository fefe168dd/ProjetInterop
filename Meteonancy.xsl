<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html" indent="yes" encoding="UTF-8"/>

    <!-- TEMPLATE PRINCIPAL -->
    <xsl:template match="/">
        <xsl:text disable-output-escaping="yes">&lt;!DOCTYPE html&gt;</xsl:text>
        <html lang="fr">
        <head>
            <meta charset="UTF-8"/>
            <link rel="stylesheet" type="text/css" href="meteo.css"/>
            <title>Météo quotidienne</title>
        </head>
        <body>
            <xsl:apply-templates select="previsions"/>
        </body>
        </html>
    </xsl:template>

    <!-- TEMPLATE PREVISIONS -->
    <xsl:template match="previsions">
        <div class="meteo-jour">
            <!-- MATIN -->
             <div class="meteo-card">

            <h3>Matin</h3>
            <xsl:variable name="matin" select="echeance[@hour &lt;= 11]"/>

            <p>🌡️ Température : 
                <xsl:value-of select="format-number($matin[1]/temperature/level[@val='2m'] - 273.15, '0.0')"/>°C 
                à 
                <xsl:value-of select="format-number($matin[last()]/temperature/level[@val='2m'] - 273.15, '0.0')"/>°C
            </p>

            <p>🌧️ Pluie :
                <xsl:choose>
                    <xsl:when test="sum($matin/pluie) &gt; 0">💧 Oui</xsl:when>
                    <xsl:otherwise>Pas de pluie</xsl:otherwise>
                </xsl:choose>
            </p>

            <p>❄️ Neige :
                <xsl:choose>
                    <xsl:when test="$matin[risque_neige='oui']">Oui</xsl:when>
                    <xsl:otherwise>Non</xsl:otherwise>
                </xsl:choose>
            </p>

            <p>💨 Vent :
                <xsl:choose>
                    <xsl:when test="$matin/vent_moyen/level[@val='10m'] &gt; 12">💨 Fort</xsl:when>
                    <xsl:otherwise>Faible</xsl:otherwise>
                </xsl:choose>
            </p>
            </div>

            <!-- MIDI -->
             <div class="meteo-card">
            <h3>Midi</h3>
            <xsl:variable name="midi" select="echeance[@hour &gt;= 12 and @hour &lt;= 16]"/>

            <p>🌡️ Température : 
                <xsl:value-of select="format-number($midi[1]/temperature/level[@val='2m'] - 273.15, '0.0')"/>°C 
                à 
                <xsl:value-of select="format-number($midi[last()]/temperature/level[@val='2m'] - 273.15, '0.0')"/>°C
            </p>

            <p>🌧️ Pluie :
                <xsl:choose>
                    <xsl:when test="sum($midi/pluie) &gt; 0">💧 Oui</xsl:when>
                    <xsl:otherwise>Pas de pluie</xsl:otherwise>
                </xsl:choose>
            </p>

            <p>❄️ Neige :
                <xsl:choose>
                    <xsl:when test="$midi[risque_neige='oui']">Oui</xsl:when>
                    <xsl:otherwise>Non</xsl:otherwise>
                </xsl:choose>
            </p>

            <p>💨 Vent :
                <xsl:choose>
                    <xsl:when test="$midi/vent_moyen/level[@val='10m'] &gt; 12">💨 Fort</xsl:when>
                    <xsl:otherwise>Faible</xsl:otherwise>
                </xsl:choose>
            </p>
        </div>

            <!-- SOIR -->
        <div class="meteo-card">
            <h3>Soir</h3>
            <xsl:variable name="soir" select="echeance[@hour &gt;= 17 and @hour &lt;= 23]"/>

            <p>🌡️ Température : 
                <xsl:value-of select="format-number($soir[1]/temperature/level[@val='2m'] - 273.15, '0.0')"/>°C 
                à 
                <xsl:value-of select="format-number($soir[last()]/temperature/level[@val='2m'] - 273.15, '0.0')"/>°C
            </p>

            <p>🌧️ Pluie :
                <xsl:choose>
                    <xsl:when test="sum($soir/pluie) &gt; 0">💧 Oui</xsl:when>
                    <xsl:otherwise>Pas de pluie</xsl:otherwise>
                </xsl:choose>
            </p>

            <p>❄️ Neige :
                <xsl:choose>
                    <xsl:when test="$soir[risque_neige='oui']">Oui</xsl:when>
                    <xsl:otherwise>Non</xsl:otherwise>
                </xsl:choose>
            </p>

            <p>💨 Vent :
                <xsl:choose>
                    <xsl:when test="$soir/vent_moyen/level[@val='10m'] &gt; 12">💨 Fort</xsl:when>
                    <xsl:otherwise>Faible</xsl:otherwise>
                </xsl:choose>
            </p>
            </div>

        </div>
    </xsl:template>

</xsl:stylesheet>
