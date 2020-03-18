package com.aplikasisertifikasi.asesor.lspabi.Main.Notification.DetailNotification;

import android.util.Log;

import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.NotificationRepository;

public class DetailNotificationPresenter implements DetailNotificationContract.Presenter {

    NotificationRepository notificationRepository = new NotificationRepository();
    private DetailNotificationContract.View view;

    public DetailNotificationPresenter(DetailNotificationContract.View view) {
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
    public void getDetailNotification(String assessmentScheduleID) {
        notificationRepository.getDetailNotification(assessmentScheduleID, new CallbackListener<SinglePayloadResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<AssessmentSchedule> assessmentScheduleSinglePayloadResponse) {
                AssessmentSchedule assessmentSchedule = assessmentScheduleSinglePayloadResponse.getPayload();

                view.setDetailNotification(assessmentScheduleSinglePayloadResponse.getPayload());
                view.setMapLatLong(Double.parseDouble(assessmentSchedule.getLatitude()), Double.parseDouble(assessmentSchedule.getLongitude()));
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void setScheduleConfirmation(String assessmentScheduleID, String assessmentScheduleStatus) {
        notificationRepository.setStatusConfirmation(assessmentScheduleID, assessmentScheduleStatus, new CallbackListener<SinglePayloadResponse<AssessmentSchedule>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<AssessmentSchedule> assessmentScheduleSinglePayloadResponse) {

            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void isReadNotification(String notificationID) {
        notificationRepository.notificationWasRead(notificationID, new CallbackListener<SinglePayloadResponse<NotificationModel>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<NotificationModel> notificationModelSinglePayloadResponse) {
                Log.d("NOTIFICATION", "IS READ");
            }

            @Override
            public void onError(Throwable throwable) {
                Log.d("NOTIFICATION", "ERROR");
            }
        });
    }
}
