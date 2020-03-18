package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

public class Applicant extends Profile {
    @SerializedName("applicant_id")
    private String applicantId;
    @SerializedName("schema_label")
    @Expose
    String competenceFieldLable;
    @SerializedName("status_graduation")
    String statusGraduation;
    @SerializedName("status_recomendation")
    String statusRecomendation;
    @SerializedName("assessment_applicant_id")
    String assessmentApplicantId;
    @SerializedName("tuk_name")
    String tukName;
    @SerializedName("description_for_recomendation")
    String descriptionRecomendation;
    @SerializedName("test_method")
    String testMethod;


    public Applicant(String statusGraduation) {
        this.statusGraduation = statusGraduation;
    }

    public Applicant(String statusRecomendation, String descriptionRecomendation) {
//        this.applicantId = applicantId;
        this.statusRecomendation = statusRecomendation;
        this.descriptionRecomendation = descriptionRecomendation;
    }

    public String getTestMethod() {
        return testMethod;
    }

    public void setTestMethod(String testMethod) {
        this.testMethod = testMethod;
    }

    public String getTukName() {
        return tukName;
    }

    public void setTukName(String tukName) {
        this.tukName = tukName;
    }

    public String getCompetenceFieldLable() {
        return competenceFieldLable;
    }

    public void setCompetenceFieldLable(String competenceFieldLable) {
        this.competenceFieldLable = competenceFieldLable;
    }

    public String getApplicantId() {
        return applicantId;
    }

    public void setApplicantId(String applicantId) {
        this.applicantId = applicantId;
    }

    public String getStatusRecomendation() {
        return statusRecomendation;
    }

    public void setStatusRecomendation(String statusRecomendation) {
        this.statusRecomendation = statusRecomendation;
    }

    public String getStatusGraduation() {
        return statusGraduation;
    }

    public void setStatusGraduation(String statusGraduation) {
        this.statusGraduation = statusGraduation;
    }

    public String getAssessmentApplicantId() {
        return assessmentApplicantId;
    }

    public void setAssessmentApplicantId(String assessmentApplicantId) {
        this.assessmentApplicantId = assessmentApplicantId;
    }

    public String getDescriptionRecomendation() {
        return descriptionRecomendation;
    }

    public void setDescriptionRecomendation(String descriptionRecomendation) {
        this.descriptionRecomendation = descriptionRecomendation;
    }
}
