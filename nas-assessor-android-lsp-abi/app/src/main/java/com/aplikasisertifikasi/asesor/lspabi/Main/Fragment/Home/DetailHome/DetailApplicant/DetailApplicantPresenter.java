package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.DetailApplicant;

import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.Portofolio;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class DetailApplicantPresenter implements DetailApplicantContract.Presenter {

    AssessmentRepository assessmentRepository = new AssessmentRepository();
    DetailApplicantContract.View view;

    DetailApplicantPresenter(DetailApplicantContract.View view) {
        this.view = view;
    }

    @Override
    public void load(Object o) {

    }

    @Override
    public void start() {
        view.initViews();
    }

    @Override
    public void end() {

    }

    @Override
    public void getDetailApplicant(String assementId, String applicantId) {
        view.showLoadingView();
        assessmentRepository.getApplicantsDetail(assementId, applicantId, new CallbackListener<SinglePayloadResponse<Applicant>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<Applicant> profileSinglePayloadResponse) {
                view.dismissLoadingView();
                view.setDetailApplicant(profileSinglePayloadResponse.getPayload());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }

    @Override
    public void getApplicantPortofolio(String assessmentId, String applicantId, String type) {
        assessmentRepository.getApplicantPortofolio(assessmentId, applicantId, type, new CallbackListener<DataPayloadListResponse<Portofolio>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(DataPayloadListResponse<Portofolio> applicantSinglePayloadResponse) {
                if (type.equals("DASAR")) {
                    view.setApplicantPortofolio(applicantSinglePayloadResponse.getPayloadList());
                } else {
                    view.setApplicantPersyaratanUmum(applicantSinglePayloadResponse.getPayloadList());
                }
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void getApplicantPeryaratan(String applicantId) {
        assessmentRepository.getApplicantPersyaratanUmum(applicantId, new CallbackListener<DataPayloadListResponse<Portofolio>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(DataPayloadListResponse<Portofolio> portofolioDataPayloadListResponse) {
                view.setApplicantPersyaratanUmum(portofolioDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void updateTestMethod(String testMethod, String assessmentId, String applicantId) {
        view.showLoadingView();
        assessmentRepository.updateTestMethod(testMethod, assessmentId, applicantId, new CallbackListener<SinglePayloadResponse<Applicant>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<Applicant> applicantSinglePayloadResponse) {
                view.dismissLoadingView();
                view.finishActivity();
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }
}
