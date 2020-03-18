package com.aplikasisertifikasi.asesor.lspabi.Api;

import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import io.reactivex.Observable;
import retrofit2.http.Header;
import retrofit2.http.Path;
import retrofit2.http.Query;

public interface NotificationService {
    interface GET {
        @retrofit2.http.GET("me/notifications?sort=-time_stamp")
        Observable<DataPayloadListResponse<NotificationModel>> getNotifications(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Query("limit") int limit,
                @Query("offset") int offset
        );

        @retrofit2.http.GET("me/schedules/assessments/{id}")
        Observable<SinglePayloadResponse<AssessmentSchedule>> getDetailNotification(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("id") String assessmentScheduleID
        );

        @retrofit2.http.GET("me/notifications/{id}")
        Observable<SinglePayloadResponse<NotificationModel>> isReadNotification(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("id") String notificationID
        );
    }

    interface PUT {
        @retrofit2.http.PUT("me/schedules/assessments/{id}/state/{status}")
        Observable<SinglePayloadResponse<AssessmentSchedule>> updateConfirmationStatus(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("id") String assessmentScheduleID,
                @Path("status") String assessmentScheduleStatus
        );
    }
}
