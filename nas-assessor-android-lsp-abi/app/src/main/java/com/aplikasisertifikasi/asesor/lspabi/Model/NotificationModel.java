package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class NotificationModel {
    @SerializedName("notification_id")
    String notificationID;
    @SerializedName("title")
    String title;
    @SerializedName("message")
    String message;
    @SerializedName("time_stamp")
    String timeStamp;
    @SerializedName("data")
    String assessmentSchedule;
    @SerializedName("is_read")
    String isRead;
    @SerializedName("count")
    String badgeCount;

    public String getNotificationID() {
        return notificationID;
    }

    public void setNotificationID(String notificationID) {
        this.notificationID = notificationID;
    }

    public String getMessage() {
        return message;
    }

    public void setMessage(String message) {
        this.message = message;
    }

    public String getTimeStamp() {
        return timeStamp;
    }

    public void setTimeStamp(String timeStamp) {
        this.timeStamp = timeStamp;
    }

    public String getAssessmentSchedule() {
        return assessmentSchedule;
    }

    public void setAssessmentSchedule(String assessmentSchedule) {
        this.assessmentSchedule = assessmentSchedule;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title;
    }

    public String getIsRead() {
        return isRead;
    }

    public void setIsRead(String isRead) {
        this.isRead = isRead;
    }

    public String getBadgeCount() {
        return badgeCount;
    }

    public void setBadgeCount(String badgeCount) {
        this.badgeCount = badgeCount;
    }

    @Override
    public String toString() {
        return "NotificationModel{" +
                "notificationID='" + notificationID + '\'' +
                ", title='" + title + '\'' +
                ", message='" + message + '\'' +
                ", timeStamp='" + timeStamp + '\'' +
                ", assessmentSchedule='" + assessmentSchedule + '\'' +
                ", isRead='" + isRead + '\'' +
                '}';
    }
}
