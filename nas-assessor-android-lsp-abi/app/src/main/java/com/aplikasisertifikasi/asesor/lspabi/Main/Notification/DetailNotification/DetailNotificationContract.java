package com.aplikasisertifikasi.asesor.lspabi.Main.Notification.DetailNotification;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;

public interface DetailNotificationContract {
    interface View extends ExtraView {
        void setDetailNotification(AssessmentSchedule assessmentSchedule);
        void setMapLatLong(Double lat, Double lng);
    }

    interface Presenter extends BasePresenter {
        void getDetailNotification(String assessmentScheduleID);
        void setScheduleConfirmation(String assessmentScheduleID, String assessmentScheduleStatus);
        void isReadNotification(String notificationID);
    }
}
