package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.AddSertifikasi;

import android.app.Activity;
import android.graphics.Bitmap;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.SubschemeCompetency;
import com.aplikasisertifikasi.asesor.lspabi.Model.SchemeCompetency;

public interface AddSertifikasiContract {

    interface View extends ExtraView {
        void setFacultyAdapter(List<String> stringList, List<SchemeCompetency> schemeCompetencyList);
        void setDepartmentFromFacultyAdapter(List<String> stringList, List<SubschemeCompetency> subschemeCompetencyList);
        void showToast(String message);
        void onUploadComplete();
        void setImageBase64(Bitmap bitmap);
        void showSendButton();
    }

    interface Presenter extends BasePresenter {
        void getSchemeCompetencies();
        void getSubschemeCompetency(String faculty_code);
        void uploadCertificate(AccessorCompetence accessorCompetence);
        void takePictFromCamera(Activity activity);
        void takePictFromGalery(Activity activity);
    }
}
