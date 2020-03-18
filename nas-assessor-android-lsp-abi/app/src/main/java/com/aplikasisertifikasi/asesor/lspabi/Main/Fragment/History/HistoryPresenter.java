package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.History;

import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class HistoryPresenter implements HistoryContract.Presenter {
    HistoryContract.View view;
    AssessmentRepository assessmentRepository = new AssessmentRepository();

    public HistoryPresenter(HistoryContract.View view) {
        this.view = view;
    }

    @Override
    public void execute(Object o) {

    }

    @Override
    public void onPause() {

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
    public void getReportAssessment() {
        view.showLoadingView();
        assessmentRepository.getReportAssessment(new CallbackListener<DataPayloadListResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<AssessmentSchedule> assessmentScheduleDataPayloadListResponse) {
                view.dismissLoadingView();
                view.setReportAssessment(assessmentScheduleDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }
}
