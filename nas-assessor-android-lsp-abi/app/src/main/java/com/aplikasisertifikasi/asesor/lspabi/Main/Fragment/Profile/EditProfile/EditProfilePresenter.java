package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile;

import android.app.Activity;
import android.content.Context;
import android.util.Log;

import com.miguelbcr.ui.rx_paparazzo2.entities.FileData;
import com.miguelbcr.ui.rx_paparazzo2.entities.Response;

import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;
import com.aplikasisertifikasi.asesor.lspabi.Utils.MyUtils;

public class EditProfilePresenter implements EditProfileContract.Presenter {
    private EditProfileContract.View view;
    UserRepository userRepository = new UserRepository();
    Context context;

    public EditProfilePresenter(EditProfileContract.View view, Context context) {
        this.view = view;
        this.context = context;
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
    public void getProfile() {
        view.showLoadingView();
        userRepository.getProfile(new CallbackListener<SinglePayloadResponse<Profile>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<Profile> response) {
                view.dismissLoadingView();
                view.setContent(response.getPayload());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
            }
        });
    }

    @Override
    public void saveEditProfile(Profile profile) {
        view.showLoadingView();
        userRepository.updateProfile(profile, new CallbackListener<Profile>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(Profile profile) {
                view.dismissLoadingView();
                view.finishActivity();
                view.showToast(context.getString(R.string.success_update_profile));
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.showToast(context.getString(R.string.failed_update_profile));
            }
        });
    }

    @Override
    public void saveImgProfile(Profile profile) {
        userRepository.updateImg(profile, new CallbackListener<Profile>() {
            @Override
            public void onCompleted() {
            }

            @Override
            public void onCompleted(Profile profile) {
            }

            @Override
            public void onError(Throwable throwable) {
                view.showToast(context.getString(R.string.failed_save_photo));
            }
        });
    }

    @Override
    public void takePictFromCamera(Activity activity, String type) {
        MyUtils.openCameraOrGallery(MyUtils.Type.CAMERA, activity, new CallbackListener<Response<Activity, FileData>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(Response<Activity, FileData> activityFileDataResponse) {
                if (type.equals("profile_pict"))
                    view.setImageBase64(MyUtils.convertToBitmap(activityFileDataResponse.data()));
                else if (type.equals("sertifikat_bnsp"))
                    view.setSertifikatBase64(activityFileDataResponse.data());
                else
                    view.setNpwpBase64(MyUtils.convertToBitmap(activityFileDataResponse.data()));
            }

            @Override
            public void onError(Throwable throwable) {
                view.showToast(context.getString(R.string.failed_get_photo));
            }
        });
    }

    @Override
    public void takePictFromGalery(Activity activity, String type) {
        MyUtils.openCameraOrGallery(MyUtils.Type.GALLERY, activity, new CallbackListener<Response<Activity, FileData>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(Response<Activity, FileData> activityFileDataResponse) {
                if (type.equals("profile_pict"))
                    view.setImageBase64(MyUtils.convertToBitmap(activityFileDataResponse.data()));
                else if (type.equals("sertifikat_bnsp"))
                    view.setSertifikatBase64(activityFileDataResponse.data());
                else
                    view.setNpwpBase64(MyUtils.convertToBitmap(activityFileDataResponse.data()));
            }

            @Override
            public void onError(Throwable throwable) {
                view.showToast(context.getString(R.string.failed_get_photo));
            }
        });
    }
}
