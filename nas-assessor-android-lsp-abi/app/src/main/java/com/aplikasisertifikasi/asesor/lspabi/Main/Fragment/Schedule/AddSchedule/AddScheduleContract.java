package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule.AddSchedule;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;

public interface AddScheduleContract {
    interface View extends ExtraView {
        void setScheduleAccessor(List<ScheduleAccessor> scheduleAccessorList);

        void showToast(String message);
    }

    interface Presenter extends BasePresenter {
        void postScheduleDates(List<ScheduleAccessor> scheduleAccessorList);

        void getScheduleAccessor();
    }
}
