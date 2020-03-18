package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile;

import android.app.Activity;
import android.graphics.Bitmap;

import com.miguelbcr.ui.rx_paparazzo2.entities.FileData;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;

public interface EditProfileContract {
    interface View extends ExtraView {
        void setContent(Profile profile);
        void showToast(String message);
        void setImageBase64(Bitmap bitmap);
        void setNpwpBase64(Bitmap bitmap);
        void setSertifikatBase64(FileData fileData);
        void finishActivity();
    }

    interface Presenter extends BasePresenter {
        void getProfile();
        void saveEditProfile(Profile profile);
        void saveImgProfile(Profile profile);
        void takePictFromCamera(Activity activity, String type);
        void takePictFromGalery(Activity activity, String type);
    }
}
