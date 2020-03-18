package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Penilaian;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragmentView;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;

public interface PenilaianContract {
    interface View extends BaseFragmentView {
        void setApplicantsList(List<Applicant> applicantsList);
        void onErrorResponse();
        void showToast(String message);
        void showLoading();
        void dismissLoading();
        void setNextPage(List<Applicant> applicants);
        void pagination();
        void showLoadProgress();
        void dismissLoadProgress();
        void updateStatusRecomendation(String status);
    }

    interface Presenter extends BasePresenter {
        void getApplicants(int limit, int offset, String assessmentId, String assessorId);
        void updateStatusAssessment(String statusRecomendation, String descRecomendation, String assessmentId, String applicantId);
        void loadNextPage(int limit, int offset, String assessmentId, String assessorId);
    }
}
