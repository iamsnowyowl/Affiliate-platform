package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.AddSertifikasi;

import android.app.Activity;

import com.miguelbcr.ui.rx_paparazzo2.entities.FileData;
import com.miguelbcr.ui.rx_paparazzo2.entities.Response;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.SubschemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SchemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.SertifikasiRepository;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

public class AddSertifikasiPresenter implements AddSertifikasiContract.Presenter {

    AddSertifikasiContract.View view;
    SertifikasiRepository sertifikasiRepository = new SertifikasiRepository();

    public AddSertifikasiPresenter(AddSertifikasiContract.View view) {
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
    public void getSchemeCompetencies() {
        sertifikasiRepository.getSchemas(new CallbackListener<DataPayloadListResponse<SchemeCompetency>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(DataPayloadListResponse<SchemeCompetency> facultyDataPayloadListResponse) {
                List<SchemeCompetency> schemas = facultyDataPayloadListResponse.getPayloadList();
                view.setFacultyAdapter(MyUtils.createSpinnerData("Skema Sertifikasi", schemas), schemas);
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void getSubschemeCompetency(String schemaId) {
        sertifikasiRepository.getSubschemas(schemaId, new CallbackListener<List<SubschemeCompetency>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(List<SubschemeCompetency> subschemeCompetencies) {
                List<SubschemeCompetency> subschemeCompetencyList = subschemeCompetencies;
                view.setDepartmentFromFacultyAdapter(MyUtils.createSpinnerSubschema("Subskema Sertifikasi", subschemeCompetencyList), subschemeCompetencyList);
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void uploadCertificate(AccessorCompetence accessorCompetence) {
        view.showLoadingView();
        sertifikasiRepository.postAccessorSkill(accessorCompetence, new CallbackListener<SinglePayloadResponse<AccessorCompetence>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<AccessorCompetence> accessorCompetenceSinglePayloadResponse) {
                if (accessorCompetenceSinglePayloadResponse.getResponseStatus().equals("SUCCESS")) {
                    view.dismissLoadingView();
                    view.startActivity(SertifikasiSaved.class);
                }
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.showToast("Gagal submit sertifikasi");
            }
        });
    }

    @Override
    public void takePictFromCamera(Activity activity) {
        MyUtils.openCameraOrGallery(MyUtils.Type.CAMERA, activity, new CallbackListener<Response<Activity, FileData>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(Response<Activity, FileData> activityFileDataResponse) {
                view.setImageBase64(MyUtils.convertToBitmap(activityFileDataResponse.data()));
                view.showSendButton();
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void takePictFromGalery(Activity activity) {
        MyUtils.openCameraOrGallery(MyUtils.Type.GALLERY, activity, new CallbackListener<Response<Activity, FileData>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(Response<Activity, FileData> activityFileDataResponse) {
                view.setImageBase64(MyUtils.convertToBitmap(activityFileDataResponse.data()));
                view.showSendButton();
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

}
