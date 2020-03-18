package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.PreAsesmen;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragmentView;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;

public interface PreAsesmenContract {
    interface View extends BaseFragmentView {
        void setApplicantsList(List<Applicant> applicantsList);
        void onErrorResponse();
        void setNextPage(List<Applicant> applicants);
        void pagination();
        void showLoadProgress();
        void dismissLoadProgress();
        void showLoading();
        void dismissLoading();
    }

    interface Presenter extends BasePresenter {
        void getApplicants(int limit, int offset, String assessmentId, String assessorId);
        void loadNextPage(int limit, int offset, String assessmentId, String assessorId);
    }
}
