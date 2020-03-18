package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.AddSchedule;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.ScheduleRepository;

public class AddSchedulePresenter implements AddScheduleContract.Presenter {

    AddScheduleContract.View view;
    ScheduleRepository scheduleRepository = new ScheduleRepository();

    public AddSchedulePresenter(AddScheduleContract.View view) {
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
    public void postScheduleDates(List<ScheduleAccessor> scheduleAccessorList) {
        view.showLoadingView();
        scheduleRepository.setScheduleAccessor(scheduleAccessorList, new CallbackListener<DataPayloadListResponse<ScheduleAccessor>>() {
            @Override
            public void onCompleted() {
            }

            @Override
            public void onCompleted(DataPayloadListResponse<ScheduleAccessor> scheduleAccessorDataPayloadListResponse) {
                view.dismissLoadingView();
                view.startActivity(ScheduleSaved.class);
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.showToast("Gagal tambah jadwal assessor, silahkan coba kembali");
            }
        });
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
                view.errorLoadingView();
            }
        });
    }
}
