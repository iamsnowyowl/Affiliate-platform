package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class HistoryModel {
    @SerializedName("accessor_competence")
    String titleAssessment;
    @SerializedName("start_date")
    String assessmentDate;
    @SerializedName("payment_flag")
    Integer paymentFlag;

    public HistoryModel(String titleAssessment, String assessmentDate, Integer paymentFlag) {
        this.titleAssessment = titleAssessment;
        this.assessmentDate = assessmentDate;
        this.paymentFlag = paymentFlag;
    }

    public String getTitleAssessment() {

        return titleAssessment;
    }

    public void setTitleAssessment(String titleAssessment) {
        this.titleAssessment = titleAssessment;
    }

    public String getAssessmentDate() {
        return assessmentDate;
    }

    public void setAssessmentDate(String assessmentDate) {
        this.assessmentDate = assessmentDate;
    }

    public Integer getPaymentFlag() {
        return paymentFlag;
    }

    public void setPaymentFlag(Integer paymentFlag) {
        this.paymentFlag = paymentFlag;
    }
}
