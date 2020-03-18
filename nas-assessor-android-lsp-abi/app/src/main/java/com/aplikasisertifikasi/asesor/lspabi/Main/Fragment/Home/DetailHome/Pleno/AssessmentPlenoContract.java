package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Pleno;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;

import java.util.List;

public interface AssessmentPlenoContract {
    interface View extends ExtraView{
        void setListApplicantPleno(List<Applicant> applicants);
        void showToast(String message);
        void setNextPage(List<Applicant> applicants);
        void pagination();
        void showLoadProgress();
        void dismissLoadProgress();
        void updateStatusGraduation(String status);
    }
    interface Presenter extends BasePresenter{
        void getListApplicantPleno(int limit, int offset, String assessmentId);
        void updateGraduateStatus(String statusGraduation, String assessmentId, String applicantId);
        void loadNextPage(int limit, int offset, String assessmentId);
    }
}
