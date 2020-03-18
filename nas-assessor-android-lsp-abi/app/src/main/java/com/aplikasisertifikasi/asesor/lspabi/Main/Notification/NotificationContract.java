package com.aplikasisertifikasi.asesor.lspabi.Main.Notification;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;

public interface NotificationContract {

    interface View extends ExtraView {
        void fetchNotifications(List<NotificationModel> notifications);
        void setNextPage(List<NotificationModel> notificationModels);
        void pagination();
        void showLoadProgress();
        void dismissLoadProgress();
        void showToast(String message);
    }

    interface Presenter extends BasePresenter {
        void getNotifications(int limit, int offset);
        void loadNextPage(int limit, int offset);
    }

}
