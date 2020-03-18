package com.aplikasisertifikasi.asesor.lspabi.Api;

import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.Portofolio;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;

import io.reactivex.Observable;
import retrofit2.http.Body;
import retrofit2.http.Field;
import retrofit2.http.FormUrlEncoded;
import retrofit2.http.Header;
import retrofit2.http.Path;
import retrofit2.http.Query;

public interface AssessmentService {
    interface GET {
        @retrofit2.http.GET("assessments?sort=start_date&last_activity_state=ADMIN_CONFIRM_FORM,ON_REVIEW_APPLICANT_DOCUMENT,ON_COMPLETED_REPORT,REAL_ASSESSMENT,PLENO_DOCUMENT_COMPLETED,PLENO_REPORT_READY")
        Observable<DataPayloadListResponse<AssessmentSchedule>> getListAssessment(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Query("limit") int limit,
                @Query("offset") int offset
        );

        @retrofit2.http.GET("assessments?last_activity_state=PRINT_CERTIFICATE,COMPLETED")
        Observable<DataPayloadListResponse<AssessmentSchedule>> getListReportAssessment(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date
        );

        @retrofit2.http.GET("assessments/{assessment_id}/letters")
        Observable<DataPayloadListResponse<AssessmentLetters>> getListSuratMenyurat(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId
        );

        @retrofit2.http.GET("assessments?sort=start_date")
        Observable<DataPayloadListResponse<AssessmentSchedule>> getListAssessmentManagement(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Query("limit") int limit,
                @Query("offset") int offset
        );

        @retrofit2.http.GET("me/notifications/count")
        Observable<NotificationModel> getNotificationsBadgeCount(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Query("is_read") String isread
        );

        @retrofit2.http.GET("assessments/{assessment_id}/applicants")
        Observable<DataPayloadListResponse<Applicant>> getListApplicants(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Query("assessor_id") String assessor_id,
                @Query("limit") int limit,
                @Query("offset") int offset
        );

        @retrofit2.http.GET("assessments/{assessment_id}/applicants")
        Observable<DataPayloadListResponse<Applicant>> getListApplicantsPleno(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Query("limit") int limit,
                @Query("offset") int offset
        );

        @retrofit2.http.GET("assessments/{assessment_id}/applicants/{applicant_id}")
        Observable<SinglePayloadResponse<Applicant>> getDetailApplicants(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Path("applicant_id") String applicantId
        );

        @retrofit2.http.GET("assessments/{assessment_id}/applicants/{assessment_applicant_id}/portfolios?limit=50&sort=form_name")
        Observable<DataPayloadListResponse<Portofolio>> getApplicantPortofolios(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Path("assessment_applicant_id") String applicantId,
                @Query("type") String type
        );

        @retrofit2.http.GET("persyaratan_umums?sort=form_name")
        Observable<DataPayloadListResponse<Portofolio>> getApplicantPersyaratanUmum(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Query("applicant_id") String applicantId
        );
    }

    interface PUT {
        @retrofit2.http.PUT("assessments/{assessment_id}/applicants/{applicant_id}")
        Observable<SinglePayloadResponse<Applicant>> updateStatusRecomendation(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Path("applicant_id") String applicantId,
                @Body Applicant applicant
        );

        @retrofit2.http.PUT("assessments/{assessment_id}/applicants/{applicant_id}")
        Observable<SinglePayloadResponse<Applicant>> updateStatusGraduation(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Path("applicant_id") String applicantId,
                @Body Applicant applicant
        );

        @FormUrlEncoded
        @retrofit2.http.PUT("assessments/{assessment_id}/applicants/{applicant_id}")
        Observable<SinglePayloadResponse<Applicant>> updateTestMethod(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Path("applicant_id") String applicantId,
                @Field("test_method") String testMethod
        );

        @retrofit2.http.PUT("assessments/{assessment_id}/letters/{letter_id}/signature")
        Observable<SinglePayloadResponse<AssessmentLetters>> assignManagementSignature(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("assessment_id") String assessmentId,
                @Path("letter_id") String letterId,
                @Body AssessmentLetters assessmentLetter
        );
    }
}
