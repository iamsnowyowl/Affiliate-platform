package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class SubschemeCompetency {
    @SerializedName("sub_schema_id")
    @Expose
    String subSchemaId;
    @SerializedName("sub_schema_number")
    @Expose
    String subSchemaNumber;
    @SerializedName("kbli")
    @Expose
    String kbli;
    @SerializedName("kbji")
    @Expose
    String kbji;
    @SerializedName("sub_schema_name")
    @Expose
    String subSchemaName;

    public String getSubSchemaId() {
        return subSchemaId;
    }

    public void setSubSchemaId(String subSchemaId) {
        this.subSchemaId = subSchemaId;
    }

    public String getKbji() {
        return kbji;
    }

    public void setKbji(String kbji) {
        this.kbji = kbji;
    }

    public String getSubSchemaName() {
        return subSchemaName;
    }

    public void setSubSchemaName(String faculty_name) {
        this.subSchemaName = subSchemaName;
    }

    public String getSubSchemaNumber() {
        return subSchemaNumber;
    }

    public void setSubSchemaNumber(String subSchemaNumber) {
        this.subSchemaNumber = subSchemaNumber;
    }

    public String getKbli() {
        return kbli;
    }

    public void setKbli(String kbli) {
        this.kbli = kbli;
    }
}
