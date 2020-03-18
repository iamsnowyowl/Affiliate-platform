package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragmentPresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;

import java.util.List;

public interface HomeContract {

    interface View extends ExtraView {
        void setAssessentList(List<AssessmentSchedule> assessentList);
        void setNextPageAssessment(List<AssessmentSchedule> assessmentScheduleList);
        void pagination();
        void showLoadProgress();
        void dismissLoadProgress();
        void setBadgeCount(NotificationModel notificationModel);
        void showToast(String message);
    }

    interface Presenter extends BaseFragmentPresenter {
        void loadNextAssessmentPage(int limit, int offset);
        void loadNextManagementPage(int limit, int offset);
        void getAssessmentManagement(int limit, int offset);
        void getAssessmentList(int limit, int offset);
        void getBadgeCount();
    }

}
