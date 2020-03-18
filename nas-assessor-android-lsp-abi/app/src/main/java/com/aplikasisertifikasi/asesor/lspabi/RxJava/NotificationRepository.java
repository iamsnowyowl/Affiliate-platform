package com.aplikasisertifikasi.asesor.lspabi.RxJava;

import com.aplikasisertifikasi.asesor.lspabi.Api.NotificationService;
import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.DigestAuthentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.RetrofitClient;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DigestHelper;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.Disposable;
import io.reactivex.schedulers.Schedulers;

public class NotificationRepository {

    NotificationService.GET notificationServiceGET = RetrofitClient.getClient().create(NotificationService.GET.class);
    NotificationService.PUT notificationServicePUT = RetrofitClient.getClient().create(NotificationService.PUT.class);

    public Disposable getNotificationList(int limit, int offset, CallbackListener<DataPayloadListResponse<NotificationModel>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/notifications");
        return notificationServiceGET.getNotifications(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), limit, offset)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe((DataPayloadListResponse<NotificationModel> notificationModelDataPayloadListResponse) -> callbackListener.onCompleted(notificationModelDataPayloadListResponse), throwable -> callbackListener.onError(new Throwable()));
    }

    public Disposable getDetailNotification(String assessmentScheduleID, CallbackListener<SinglePayloadResponse<AssessmentSchedule>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/schedules/assessments/" + assessmentScheduleID);
        return notificationServiceGET.getDetailNotification(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentScheduleID)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentScheduleSinglePayloadResponse -> callbackListener.onCompleted(assessmentScheduleSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable setStatusConfirmation(String assessmentScheduleID, String assessmentScheduleStatus, CallbackListener<SinglePayloadResponse<AssessmentSchedule>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/me/schedules/assessments/" + assessmentScheduleID + "/state/" + assessmentScheduleStatus);
        return notificationServicePUT.updateConfirmationStatus(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentScheduleID, assessmentScheduleStatus)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentScheduleSinglePayloadResponse -> callbackListener.onCompleted(assessmentScheduleSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable notificationWasRead(String notificationID, CallbackListener<SinglePayloadResponse<NotificationModel>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/notifications/" + notificationID);
        return notificationServiceGET.isReadNotification(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), notificationID)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(notificationModelSinglePayloadResponse -> callbackListener.onCompleted(notificationModelSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }
}
