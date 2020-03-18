package com.aplikasisertifikasi.asesor.lspabi.RxJava;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.Api.ScheduleService;
import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.DigestAuthentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.RetrofitClient;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DigestHelper;
import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.Disposable;
import io.reactivex.schedulers.Schedulers;

public class ScheduleRepository {

    private ScheduleService.POST scheduleServicePOST = RetrofitClient.getClient().create(ScheduleService.POST.class);
    private ScheduleService.GET scheduleServiceGET = RetrofitClient.getClient().create(ScheduleService.GET.class);

    public Disposable setScheduleAccessor(List<ScheduleAccessor> scheduleAccessorList, CallbackListener<DataPayloadListResponse<ScheduleAccessor>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("POST", "/schedules/accessors");
        return scheduleServicePOST.setScheduleAccessor(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), scheduleAccessorList)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(scheduleAccessorDataPayloadListResponse -> callbackListener.onCompleted(scheduleAccessorDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getScheduleAccessor(CallbackListener<DataPayloadListResponse<ScheduleAccessor>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/schedules/accessors");
        return scheduleServiceGET.getSchedule(digestAuthentication.getAuthorization(), digestAuthentication.getDate())
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(scheduleAccessorDataPayloadListResponse -> callbackListener.onCompleted(scheduleAccessorDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }
}
