package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SchemeCompetency {
    @SerializedName("schema_id")
    @Expose
    String schemaId;
    @SerializedName("skkni")
    @Expose
    String skkni;
    @SerializedName("schema_name")
    @Expose
    String schemaName;

    public SchemeCompetency(String schemaId, String schemaName) {
        this.skkni = schemaId;
        this.schemaName = schemaName;
    }

    public String getSkkni() {
        return skkni;
    }

    public void setSkkni(String skkni) {
        this.skkni = skkni;
    }

    public String getSchemaName() {
        return schemaName;
    }

    public void setSchemaName(String schemaName) {
        this.schemaName = schemaName;
    }

    public String getSchemaId() {
        return schemaId;
    }

    public void setSchemaId(String schemaId) {
        this.schemaId = schemaId;
    }
}
