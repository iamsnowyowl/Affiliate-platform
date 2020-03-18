package com.aplikasisertifikasi.asesor.lspabi.RxJava;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.Api.AccessorCompetenceService;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.SubschemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.DigestAuthentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.SchemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.RetrofitClient;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DigestHelper;
import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.Disposable;
import io.reactivex.schedulers.Schedulers;

public class SertifikasiRepository {

    AccessorCompetenceService.GET facultyServiceGET = RetrofitClient.getClient().create(AccessorCompetenceService.GET.class);
    AccessorCompetenceService.GET deparetmentServiceGET = RetrofitClient.getClient().create(AccessorCompetenceService.GET.class);
    AccessorCompetenceService.GET accessorCompetenceGET = RetrofitClient.getClient().create(AccessorCompetenceService.GET.class);
    AccessorCompetenceService.POST accessorCompetencePOST = RetrofitClient.getClient().create(AccessorCompetenceService.POST.class);

    public Disposable getSchemas(CallbackListener<DataPayloadListResponse<SchemeCompetency>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/schemas");
        return facultyServiceGET.getSchema(digestAuthentication.getAuthorization(), digestAuthentication.getDate())
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(facultyDataPayloadListResponse -> callbackListener.onCompleted(facultyDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getSubschemas(String schemaId, CallbackListener<List<SubschemeCompetency>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/schemas/" + schemaId + "/sub_schemas");
        return deparetmentServiceGET.getSubschema(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), schemaId)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(departmentDataPayloadListResponse -> callbackListener.onCompleted(departmentDataPayloadListResponse.getPayloadList()), throwable -> callbackListener.onError(throwable));
    }

    public Disposable postAccessorSkill(AccessorCompetence accessorCompetence, CallbackListener<SinglePayloadResponse<AccessorCompetence>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("POST", "/me/accessor/competences");
        return accessorCompetencePOST.postAccessorSkill(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), accessorCompetence)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(accessorCompetenceSinglePayloadResponse -> callbackListener.onCompleted(accessorCompetenceSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getAccessorSkills(CallbackListener<DataPayloadListResponse<AccessorCompetence>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/accessor/competences");
        return accessorCompetenceGET.getAccessorSkill(digestAuthentication.getAuthorization(), digestAuthentication.getDate())
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(accessorCompetenceDataPayloadListResponse -> callbackListener.onCompleted(accessorCompetenceDataPayloadListResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getDetailAccessorSkill(String id_accessor_skill, CallbackListener<SinglePayloadResponse<AccessorCompetence>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me/accessor/competences/" + id_accessor_skill);
        return accessorCompetenceGET.getDetailAccessorSkill(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), id_accessor_skill)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(accessorCompetenceSinglePayloadResponse -> callbackListener.onCompleted(accessorCompetenceSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

}