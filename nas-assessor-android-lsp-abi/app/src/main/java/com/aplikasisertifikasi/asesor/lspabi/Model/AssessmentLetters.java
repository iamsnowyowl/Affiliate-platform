package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class AssessmentLetters {
    @SerializedName("assessment_id")
    String assessmentId;
    @SerializedName("assessment_letter_id")
    String letterId;
    @SerializedName("assessment_letter_name")
    String namaSurat;
    @SerializedName("signature_flag")
    String signatureFlag;
    @SerializedName("letter_type")
    String letterType;
    @SerializedName("url")
    String url;
    @SerializedName("reason_declined_signature")
    String description;

    public String getAssessmentId() {
        return assessmentId;
    }

    public void setAssessmentId(String assessmentId) {
        this.assessmentId = assessmentId;
    }

    public String getLetterId() {
        return letterId;
    }

    public void setLetterId(String letterId) {
        this.letterId = letterId;
    }

    public String getNamaSurat() {
        return namaSurat;
    }

    public void setNamaSurat(String namaSurat) {
        this.namaSurat = namaSurat;
    }

    public String getLetterType() {
        return letterType;
    }

    public void setLetterType(String letterType) {
        this.letterType = letterType;
    }

    public String getSignatureFlag() {
        return signatureFlag;
    }

    public void setSignatureFlag(String signatureFlag) {
        this.signatureFlag = signatureFlag;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getUrl() {
        return url;
    }

    public void setUrl(String url) {
        this.url = url;
    }
}
