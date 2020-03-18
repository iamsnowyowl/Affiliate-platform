package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home;

import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

import java.util.ArrayList;

public class HomePresenter implements HomeContract.Presenter {
    private HomeContract.View view;
    AssessmentRepository assessmentRepository = new AssessmentRepository();

    public HomePresenter(HomeContract.View view) {
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
        view.pagination();
    }

    @Override
    public void end() {

    }

    @Override
    public void loadNextAssessmentPage(int limit, int offset) {
        view.showLoadProgress();
        assessmentRepository.getAssessmentList(limit, offset, new CallbackListener<DataPayloadListResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(DataPayloadListResponse<AssessmentSchedule> assessmentScheduleDataPayloadListResponse) {
                view.dismissLoadProgress();
                view.setNextPageAssessment(assessmentScheduleDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadProgress();
                view.errorLoadingView();
            }
        });
    }

    @Override
    public void loadNextManagementPage(int limit, int offset) {
        view.showLoadProgress();
        assessmentRepository.getAssessmentManagement(limit, offset, new CallbackListener<DataPayloadListResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(DataPayloadListResponse<AssessmentSchedule> assessmentScheduleDataPayloadListResponse) {
                view.dismissLoadProgress();
                view.setNextPageAssessment(assessmentScheduleDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadProgress();
                view.errorLoadingView();
            }
        });
    }

    @Override
    public void getAssessmentManagement(int limit, int offset) {
        view.showLoadingView();
        view.setAssessentList(new ArrayList<>());
        assessmentRepository.getAssessmentManagement(limit, offset, new CallbackListener<DataPayloadListResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<AssessmentSchedule> assessmentScheduleDataPayloadListResponse) {
                view.dismissLoadingView();
                view.setAssessentList(assessmentScheduleDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }

    @Override
    public void getAssessmentList(int limit, int offset) {
        view.showLoadingView();
        view.setAssessentList(new ArrayList<>());

        assessmentRepository.getAssessmentList(limit, offset, new CallbackListener<DataPayloadListResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<AssessmentSchedule> assessmentScheduleDataPayloadListResponse) {
                view.dismissLoadingView();
                view.setAssessentList(assessmentScheduleDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }

    @Override
    public void getBadgeCount() {
        assessmentRepository.getNotificationBadgeCount(new CallbackListener<NotificationModel>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(NotificationModel notificationModel) {
                view.setBadgeCount(notificationModel);
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }
}
