package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class AssessmentSchedule {
    @SerializedName("title")
    String title;
    @SerializedName("longitude")
    String longitude;
    @SerializedName("latitude")
    String latitude;
    @SerializedName("notes")
    String notes;
    @SerializedName("address")
    String address;
    @SerializedName("description")
    String description;
    @SerializedName("start_date")
    String startDate;
    @SerializedName("end_date")
    String endDate;
    @SerializedName("last_state_assessor")
    String lastStateAssessor;
    @SerializedName("start_time_offering")
    String startTimeOffering;
    @SerializedName("end_time_offering")
    String endTimeOffering;
    @SerializedName("assessment_id")
    String scheduleAssessmentID;
    @SerializedName("tuk_name")
    String tukId;
    @SerializedName("last_activity_state")
    String lastStateSchedule;
    @SerializedName("accessor_name")
    String assessorName;
    @SerializedName("competence_field_lable")
    String competenceFieldLable;
    @SerializedName("payment_flag")
    String paymenFlag;
    @SerializedName("count")
    String count;
    @SerializedName("is_user_assessment")
    int isUserAssessment;
    @SerializedName("is_user_pleno")
    int isUserPleno;
    @SerializedName("count_recomendation")
    int countRecomendation;
    @SerializedName("count_emptyrecomendation")
    int countEmptyRecomendation;
    @SerializedName("count_graduation")
    int countGraduation;
    @SerializedName("count_emptygraduation")
    int countEmptyGraduation;

    public String getCount() {
        return count;
    }

    public void setCount(String count) {
        this.count = count;
    }

    public String getPaymenFlag() {
        return paymenFlag;
    }

    public void setPaymenFlag(String paymenFlag) {
        this.paymenFlag = paymenFlag;
    }

    public String getLastStateAssessor() {
        return lastStateAssessor;
    }

    public void setLastStateAssessor(String lastStatusOffering) {
        this.lastStateAssessor = lastStatusOffering;
    }

    public String getStartTimeOffering() {
        return startTimeOffering;
    }

    public void setStartTimeOffering(String startTimeOffering) {
        this.startTimeOffering = startTimeOffering;
    }

    public String getEndTimeOffering() {
        return endTimeOffering;
    }

    public void setEndTimeOffering(String endTimeOffering) {
        this.endTimeOffering = endTimeOffering;
    }

    public String getScheduleAssessmentID() {
        return scheduleAssessmentID;
    }

    public void setScheduleAssessmentID(String scheduleAssessmentID) {
        this.scheduleAssessmentID = scheduleAssessmentID;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getLongitude() {
        return longitude;
    }

    public void setLongitude(String longitude) {
        this.longitude = longitude;
    }

    public String getLatitude() {
        return latitude;
    }

    public void setLatitude(String latitude) {
        this.latitude = latitude;
    }

    public String getNotes() {
        return notes;
    }

    public void setNotes(String notes) {
        this.notes = notes;
    }

    public String getAddress() {
        return address;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    public String getStartDate() {
        return startDate;
    }

    public void setStartDate(String startDate) {
        this.startDate = startDate;
    }

    public String getEndDate() {
        return endDate;
    }

    public void setEndDate(String endDate) {
        this.endDate = endDate;
    }

    public String getTukId() {
        return tukId;
    }

    public void setTukId(String tukId) {
        this.tukId = tukId;
    }

    public String getLastStateSchedule() {
        return lastStateSchedule;
    }

    public void setLastStateSchedule(String lastStateSchedule) {
        this.lastStateSchedule = lastStateSchedule;
    }

    public String getAssessorName() {
        return assessorName;
    }

    public void setAssessorName(String assessorName) {
        this.assessorName = assessorName;
    }

    public String getCompetenceFieldLable() {
        return competenceFieldLable;
    }

    public void setCompetenceFieldLable(String competenceFieldLable) {
        this.competenceFieldLable = competenceFieldLable;
    }

    public int getIsUserAssessment() {
        return isUserAssessment;
    }

    public void setIsUserAssessment(int isUserAssessment) {
        this.isUserAssessment = isUserAssessment;
    }

    public int getIsUserPleno() {
        return isUserPleno;
    }

    public void setIsUserPleno(int isUserPleno) {
        this.isUserPleno = isUserPleno;
    }

    public int getCountRecomendation() {
        return countRecomendation;
    }

    public void setCountRecomendation(int countRecomendation) {
        this.countRecomendation = countRecomendation;
    }

    public int getCountEmptyRecomendation() {
        return countEmptyRecomendation;
    }

    public void setCountEmptyRecomendation(int countEmptyRecomendation) {
        this.countEmptyRecomendation = countEmptyRecomendation;
    }

    public int getCountGraduation() {
        return countGraduation;
    }

    public void setCountGraduation(int countGraduation) {
        this.countGraduation = countGraduation;
    }

    public int getCountEmptyGraduation() {
        return countEmptyGraduation;
    }

    public void setCountEmptyGraduation(int countEmptyGraduation) {
        this.countEmptyGraduation = countEmptyGraduation;
    }
}
