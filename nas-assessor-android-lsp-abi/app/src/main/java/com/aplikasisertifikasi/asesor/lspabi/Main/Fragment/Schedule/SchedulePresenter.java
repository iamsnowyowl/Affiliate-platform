package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule;


import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleModel;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.ScheduleRepository;

public class SchedulePresenter implements ScheduleContract.Presenter {

    ScheduleContract.View view;
    ScheduleRepository scheduleRepository = new ScheduleRepository();

    public SchedulePresenter(ScheduleContract.View view) {
        this.view = view;
    }

    @Override
    public void execute(Object o) {
    }

    @Override
    public void onPause() {
    }

    @Override
    public void load(Object api_key) {
    }

    @Override
    public void start() {
        view.initViews();
    }

    @Override
    public void end() {
    }

    @Override
    public void getScheduleAccessor() {
        scheduleRepository.getScheduleAccessor(new CallbackListener<DataPayloadListResponse<ScheduleAccessor>>() {
            @Override
            public void onCompleted() {
            }

            @Override
            public void onCompleted(DataPayloadListResponse<ScheduleAccessor> scheduleAccessorDataPayloadListResponse) {
                view.setScheduleAccessor(scheduleAccessorDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
            }
        });
    }
}
