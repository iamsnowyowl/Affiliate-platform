package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Pleno;

import android.content.Context;

import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class AssessmentPlenoPresenter implements AssessmentPlenoContract.Presenter {

    AssessmentPlenoContract.View view;
    AssessmentRepository assessmentRepository = new AssessmentRepository();
    Context context;

    public AssessmentPlenoPresenter(AssessmentPlenoContract.View view, Context context) {
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
    public void getListApplicantPleno(int limit, int offset, String assessmentId) {
        view.showLoadingView();
        assessmentRepository.getApplicantsListPleno(limit, offset, assessmentId, new CallbackListener<DataPayloadListResponse<Applicant>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<Applicant> applicantDataPayloadListResponse) {
                view.dismissLoadingView();
                view.setListApplicantPleno(applicantDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.showToast(context.getString(R.string.load_failed));
            }
        });
    }

    @Override
    public void updateGraduateStatus(String statusGraduation, String assessmentId, String applicantId) {
        view.showLoadingView();
        assessmentRepository.updateGraduation(new Applicant(statusGraduation), assessmentId, applicantId, new CallbackListener<SinglePayloadResponse<Applicant>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<Applicant> applicantSinglePayloadResponse) {
                view.dismissLoadingView();
                view.showToast(context.getString(R.string.success));
                view.updateStatusGraduation(statusGraduation);
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.showToast(context.getString(R.string.failed_send_report_pleno));
                view.updateStatusGraduation("GAGAL");
            }
        });
    }

    @Override
    public void loadNextPage(int limit, int offset, String assessmentId) {
        view.showLoadProgress();
        assessmentRepository.getApplicantsListPleno(limit, offset, assessmentId, new CallbackListener<DataPayloadListResponse<Applicant>>() {
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
