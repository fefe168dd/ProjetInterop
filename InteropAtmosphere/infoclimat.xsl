<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">
        <div id="Meteo">
            <div>
                <h3>Matin</h3>
                <h3>🌇</h3>
                <xsl:apply-templates select="//echeance[@hour = 9 ]"/>
            </div>
            <div>
                <h3>Midi</h3>
                <h3>🏙️</h3>
                <xsl:apply-templates select="//echeance[@hour = 12]"/>
            </div>
            <div>
                <h3>Soir</h3>
                <h3>🌆</h3>
                <xsl:apply-templates select="//echeance[@hour = 18]"/>
            </div>
            <div>
                <h3>Nuit</h3>
                <h3>🌃</h3>
                <xsl:apply-templates select="//echeance[@hour = 3]"/>
            </div>
        </div>
    </xsl:template>

    <xsl:template match="echeance">
        <h3>
            <xsl:choose>
                <xsl:when test="risque_neige = 'oui'">
                    <span>☁️🌨️☁️</span>
                </xsl:when>
                <xsl:when test="pluie &gt; 0 and nebulosite/totale &gt; 50">
                    <span>☁️🌧️☁️</span>
                </xsl:when>
                <xsl:when test="pluie &gt; 0">
                    <span>☁️🌦️☁️</span>
                </xsl:when>
                <xsl:when test="nebulosite/totale &gt; 50">
                    <span>☁️🌥️☁️</span>
                </xsl:when>
                <xsl:otherwise>
                    <span>☀️</span>
                </xsl:otherwise>
            </xsl:choose>
        </h3>
        <p>Humidité 💧(2m) <xsl:value-of select="humidite/level[@val='2m']"></xsl:value-of>%</p>
        <p>Vent moyen 🍃 (10m) <xsl:value-of select="vent_moyen/level[@val='10m']"></xsl:value-of> m/s</p>
        <p>Rafales 💨 (10m) <xsl:value-of select="vent_rafales/level[@val='10m']"></xsl:value-of> m/s</p>
        <p>Temperature 🌡️ (2m) <xsl:value-of select="format-number(temperature/level[@val='2m'] - 273.15, '0.00')"/>°C</p>
    </xsl:template>
</xsl:stylesheet>
