package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class AccessorCompetence {

    @SerializedName("accessor_competence_id")
    @Expose
    String accessorCompetenceId;
    @SerializedName("sub_schema_number")
    @Expose
    String subSchemaNumber;
    @SerializedName("sub_schema_name")
    @Expose
    String subSchemaName;
    @SerializedName("image_b64")
    @Expose
    String imageBase64;
    @SerializedName("certificate_file")
    @Expose
    String certificateFile;
    @SerializedName("verification_date")
    @Expose
    String verificationDate;
    @SerializedName("verification_flag")
    @Expose
    Integer verificationFlag;
    @SerializedName("expired_date")
    @Expose
    String ExpiredDate;
    @SerializedName("expired_flag")
    @Expose
    Integer expiredFlag;
    @SerializedName("created_date")
    @Expose
    String createdDate;
    String status;

    public String getAccessorCompetenceId() {
        return accessorCompetenceId;
    }

    public void setAccessorCompetenceId(String accessorCompetenceId) {
        this.accessorCompetenceId = accessorCompetenceId;
    }

    public String getSubSchemaNumber() {
        return subSchemaNumber;
    }

    public void setSubSchemaNumber(String subSchemaNumber) {
        this.subSchemaNumber = subSchemaNumber;
    }

    public String getSubSchemaName() {
        return subSchemaName;
    }

    public void setSubSchemaName(String subSchemaName) {
        this.subSchemaName = subSchemaName;
    }

    public String getImageBase64() {
        return imageBase64;
    }

    public void setImageBase64(String imageBase64) {
        this.imageBase64 = imageBase64;
    }

    public String getCertificateFile() {
        return certificateFile;
    }

    public void setCertificateFile(String certificateFile) {
        this.certificateFile = certificateFile;
    }

    public String getVerificationDate() {
        return verificationDate;
    }

    public void setVerificationDate(String verificationDate) {
        this.verificationDate = verificationDate;
    }

    public Integer getVerificationFlag() {
        return verificationFlag;
    }

    public void setVerificationFlag(Integer verificationFlag) {
        this.verificationFlag = verificationFlag;
    }

    public String getExpiredDate() {
        return ExpiredDate;
    }

    public void setExpiredDate(String expiredDate) {
        ExpiredDate = expiredDate;
    }

    public Integer getExpiredFlag() {
        return expiredFlag;
    }

    public void setExpiredFlag(Integer expiredFlag) {
        this.expiredFlag = expiredFlag;
    }

    public String getCreatedDate() {
        return createdDate;
    }

    public void setCreatedDate(String createdDate) {
        this.createdDate = createdDate;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status;
    }

    @Override
    public String toString() {
        return "AccessorCompetence{" +
                "accessorCompetenceId='" + accessorCompetenceId + '\'' +
                ", subSchemaNumber='" + subSchemaNumber + '\'' +
                ", subSchemaName='" + subSchemaName + '\'' +
                ", imageBase64='" + imageBase64 + '\'' +
                ", certificateFile='" + certificateFile + '\'' +
                ", verificationDate='" + verificationDate + '\'' +
                ", verificationFlag=" + verificationFlag +
                ", ExpiredDate='" + ExpiredDate + '\'' +
                ", expiredFlag=" + expiredFlag +
                ", createdDate='" + createdDate + '\'' +
                ", status='" + status + '\'' +
                '}';
    }
}
