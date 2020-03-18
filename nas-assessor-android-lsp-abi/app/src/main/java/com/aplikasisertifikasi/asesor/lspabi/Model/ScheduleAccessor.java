package com.aplikasisertifikasi.asesor.lspabi.Model;

import com.google.gson.annotations.SerializedName;

public class ScheduleAccessor {

    @SerializedName("CalendarDay")
    String scheduleDates;

    public String getScheduleDates() {
        return scheduleDates;
    }

    public void setScheduleDates(String scheduleDates) {
        this.scheduleDates = scheduleDates;
    }

    @Override
    public String toString() {
        return scheduleDates;
    }
}
