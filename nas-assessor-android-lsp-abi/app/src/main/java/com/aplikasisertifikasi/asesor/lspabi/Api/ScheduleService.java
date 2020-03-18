package com.aplikasisertifikasi.asesor.lspabi.Api;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.ScheduleAccessor;
import io.reactivex.Observable;
import retrofit2.http.Body;
import retrofit2.http.Header;

public interface ScheduleService {

    interface GET {
        @retrofit2.http.GET("me/schedules/accessors?limit=100&sort=CalendarDay")
        Observable<DataPayloadListResponse<ScheduleAccessor>> getSchedule(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date
        );
    }

    interface POST {
        @retrofit2.http.POST("schedules/accessors")
        Observable<DataPayloadListResponse<ScheduleAccessor>> setScheduleAccessor(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Body List<ScheduleAccessor> scheduleAccessor
        );
    }
}
