package com.aplikasisertifikasi.asesor.lspabi.RxJava;

import com.aplikasisertifikasi.asesor.lspabi.Api.AssessmentService;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.DigestAuthentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.Portofolio;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.RetrofitClient;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DigestHelper;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.Disposable;
import io.reactivex.schedulers.Schedulers;

public class AssessmentRepository {

    AssessmentService.GET assessmentServiceGET = RetrofitClient.getClient().create(AssessmentService.GET.class);
    AssessmentService.PUT assessmentServicePUT = RetrofitClient.getClient().create(AssessmentService.PUT.class);

    public Disposable getAssessmentList(int limit, int offset, CallbackListener<DataPayloadListResponse<AssessmentSchedule>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments");
        return assessmentServiceGET.getListAssessment(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), limit, offset)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentScheduleDataPayloadListResponse -> callbackListener.onCompleted(assessmentScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getNotificationBadgeCount(CallbackListener<NotificationModel> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/notifications/count");
        return assessmentServiceGET.getNotificationsBadgeCount(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), "0")
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(notificationModel -> callbackListener.onCompleted(notificationModel), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getApplicantsList(int limit, int offset, String assessmentId, String assessorId, CallbackListener<DataPayloadListResponse<Applicant>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments/" + assessmentId + "/applicants");
        return assessmentServiceGET.getListApplicants(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, assessorId, limit, offset)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantsScheduleDataPayloadListResponse -> callbackListener.onCompleted(applicantsScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getApplicantsListPleno(int limit, int offset, String assessmentId, CallbackListener<DataPayloadListResponse<Applicant>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments/" + assessmentId + "/applicants");
        return assessmentServiceGET.getListApplicantsPleno(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, limit, offset)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantsScheduleDataPayloadListResponse -> callbackListener.onCompleted(applicantsScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getApplicantsDetail(String assessmentId, String applicantId, CallbackListener<SinglePayloadResponse<Applicant>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments/" + assessmentId + "/applicants/" + applicantId);
        return assessmentServiceGET.getDetailApplicants(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, applicantId)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantsScheduleDataPayloadListResponse -> callbackListener.onCompleted(applicantsScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getApplicantPortofolio(String assessmentId, String applicantId, String type, CallbackListener<DataPayloadListResponse<Portofolio>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments/" + assessmentId + "/applicants/" + applicantId + "/portfolios");
        return assessmentServiceGET.getApplicantPortofolios(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, applicantId, type)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantsScheduleDataPayloadListResponse -> callbackListener.onCompleted(applicantsScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getApplicantPersyaratanUmum(String applicantId, CallbackListener<DataPayloadListResponse<Portofolio>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/persyaratan_umums");
        return assessmentServiceGET.getApplicantPersyaratanUmum(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), applicantId)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantsScheduleDataPayloadListResponse -> callbackListener.onCompleted(applicantsScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getAssessmentManagement(int limit, int offset, CallbackListener<DataPayloadListResponse<AssessmentSchedule>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments");
        return assessmentServiceGET.getListAssessmentManagement(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), limit, offset)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentScheduleDataPayloadListResponse -> callbackListener.onCompleted(assessmentScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getAssessmentLetters(String assessmentId, CallbackListener<DataPayloadListResponse<AssessmentLetters>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments/" + assessmentId + "/letters");
        return assessmentServiceGET.getListSuratMenyurat(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentScheduleDataPayloadListResponse -> callbackListener.onCompleted(assessmentScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getReportAssessment(CallbackListener<DataPayloadListResponse<AssessmentSchedule>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/assessments");
        return assessmentServiceGET.getListReportAssessment(digestAuthentication.getAuthorization(), digestAuthentication.getDate())
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentScheduleDataPayloadListResponse -> callbackListener.onCompleted(assessmentScheduleDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable updateRecomendation(Applicant applicant, String assessmentId, String applicantId, CallbackListener<SinglePayloadResponse<Applicant>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/assessments/" + assessmentId + "/applicants/" + applicantId);
        return assessmentServicePUT.updateStatusRecomendation(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, applicantId, applicant)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantSinglePayloadResponse -> callbackListener.onCompleted(applicantSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable assignSignature(AssessmentLetters assessmentLetter, String assessmentId, String letterId, CallbackListener<SinglePayloadResponse<AssessmentLetters>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/assessments/" + assessmentId + "/letters/" + letterId + "/signature");
        return assessmentServicePUT.assignManagementSignature(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, letterId, assessmentLetter)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(assessmentLettersSinglePayloadResponse -> callbackListener.onCompleted(assessmentLettersSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable updateGraduation(Applicant applicant, String assessmentId, String applicantId, CallbackListener<SinglePayloadResponse<Applicant>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/assessments/" + assessmentId + "/applicants/" + applicantId);
        return assessmentServicePUT.updateStatusGraduation(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, applicantId, applicant)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantSinglePayloadResponse -> callbackListener.onCompleted(applicantSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable updateTestMethod(String testMethod, String assessmentId, String applicantId, CallbackListener<SinglePayloadResponse<Applicant>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/assessments/" + assessmentId + "/applicants/" + applicantId);
        return assessmentServicePUT.updateTestMethod(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), assessmentId, applicantId, testMethod)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(applicantSinglePayloadResponse -> callbackListener.onCompleted(applicantSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }
}
