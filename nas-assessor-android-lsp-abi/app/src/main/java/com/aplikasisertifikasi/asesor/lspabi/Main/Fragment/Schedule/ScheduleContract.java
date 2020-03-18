package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Schedule;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragmentPresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;

public interface ScheduleContract {

    interface View extends ExtraView {
        void setScheduleAccessor(List<ScheduleAccessor> scheduleAccessorList);
        void showSnackBar(String message);
    }

    interface Presenter extends BaseFragmentPresenter {
        void getScheduleAccessor();
    }
}
