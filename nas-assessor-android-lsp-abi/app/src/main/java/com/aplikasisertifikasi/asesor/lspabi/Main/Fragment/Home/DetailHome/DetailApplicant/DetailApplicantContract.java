package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.DetailApplicant;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.Portofolio;

public interface DetailApplicantContract {
    interface View extends ExtraView {
        void setDetailApplicant(Applicant profile);
        void setApplicantPortofolio(List<Portofolio> portofolios);
        void setApplicantPersyaratanUmum(List<Portofolio> portofolios);
        void finishActivity();
    }

    interface Presenter extends BasePresenter {
        void getDetailApplicant(String assementId, String applicantId);
        void getApplicantPortofolio(String assementId, String applicantId, String type);
        void getApplicantPeryaratan(String applicantId);
        void updateTestMethod(String testMethod, String assementId, String applicantId);
    }
}
