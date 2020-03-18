package com.aplikasisertifikasi.asesor.lspabi.Main.Notification;

import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.NotificationRepository;

import java.util.ArrayList;

public class NotificationsPresenter implements NotificationContract.Presenter {
    private NotificationContract.View view;
    NotificationRepository notificationRepository = new NotificationRepository();

    public NotificationsPresenter(NotificationContract.View v) {
        this.view = v;
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
    public void getNotifications(int limit, int offset) {
        view.showLoadingView();
        view.fetchNotifications(new ArrayList<>());
        notificationRepository.getNotificationList(limit, offset, new CallbackListener<DataPayloadListResponse<NotificationModel>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<NotificationModel> notificationModelDataPayloadListResponse) {
                view.dismissLoadingView();
                view.fetchNotifications(notificationModelDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
            }
        });
    }

    @Override
    public void loadNextPage(int limit, int offset) {
        view.showLoadProgress();
        view.setNextPage(new ArrayList<>());
        notificationRepository.getNotificationList(limit, offset, new CallbackListener<DataPayloadListResponse<NotificationModel>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadProgress();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<NotificationModel> notificationModelDataPayloadListResponse) {
                view.dismissLoadProgress();
                view.setNextPage(notificationModelDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadProgress();
                view.showToast("No more item available");
            }
        });
    }
}
