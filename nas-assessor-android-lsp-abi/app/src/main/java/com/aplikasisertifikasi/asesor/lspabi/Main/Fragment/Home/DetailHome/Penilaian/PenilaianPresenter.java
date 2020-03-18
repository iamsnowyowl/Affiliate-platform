package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Penilaian;

import android.content.Context;
import android.util.Log;

import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class PenilaianPresenter implements PenilaianContract.Presenter {

    private PenilaianContract.View view;
    AssessmentRepository assessmentRepository = new AssessmentRepository();
    Context context;

    public PenilaianPresenter(PenilaianContract.View view, Context context) {
        this.view = view;
        this.context = context;
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
    public void updateStatusAssessment(String statusRecomendation, String descRecomendation, String assessmentId, String applicantId) {
        view.showLoading();
        assessmentRepository.updateRecomendation(new Applicant(statusRecomendation, descRecomendation), assessmentId, applicantId, new CallbackListener<SinglePayloadResponse<Applicant>>() {
            @Override
            public void onCompleted() {
                view.dismissLoading();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<Applicant> applicantSinglePayloadResponse) {
                view.dismissLoading();
//                view.showToast(context.getString(R.string.recomendation_saved));
                view.updateStatusRecomendation(statusRecomendation);
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoading();
//                view.showToast(context.getString(R.string.recomendation_fail));
                view.updateStatusRecomendation("GAGAL");
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
            }
        });
    }
}
