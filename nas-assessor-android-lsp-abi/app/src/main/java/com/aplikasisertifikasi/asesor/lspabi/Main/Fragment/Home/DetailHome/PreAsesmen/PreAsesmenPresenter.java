package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.PreAsesmen;

import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class PreAsesmenPresenter implements PreAsesmenContract.Presenter {

    AssessmentRepository assessmentRepository = new AssessmentRepository();
    private PreAsesmenContract.View view;

    public PreAsesmenPresenter(PreAsesmenContract.View view) {
        this.view = view;
    }

    @Override
    public void load(Object o) {

    }

    @Override
    public void start() {
        view.initViews();
        view.pagination();
    }

    @Override
    public void end() {

    }

    @Override
    public void getApplicants(int limit, int offset, String assessmentId, String assessorId) {
        view.showLoading();
        assessmentRepository.getApplicantsList(limit, offset, assessmentId, assessorId, new CallbackListener<DataPayloadListResponse<Applicant>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(DataPayloadListResponse<Applicant> profileDataPayloadListResponse) {
                view.dismissLoading();
                view.setApplicantsList(profileDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoading();
                view.onErrorResponse();
            }
        });
    }

    @Override
    public void loadNextPage(int limit, int offset, String assessmentId, String assessorId) {
        view.showLoadProgress();
        assessmentRepository.getApplicantsList(limit, offset, assessmentId, assessorId, new CallbackListener<DataPayloadListResponse<Applicant>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadProgress();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<Applicant> profileDataPayloadListResponse) {
                view.dismissLoadProgress();
                view.setNextPage(profileDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadProgress();
                view.onErrorResponse();
            }
        });
    }
}
