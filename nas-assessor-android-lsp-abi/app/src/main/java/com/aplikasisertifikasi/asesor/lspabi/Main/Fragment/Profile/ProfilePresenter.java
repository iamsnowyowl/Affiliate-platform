package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile;

import android.content.Context;
import android.support.annotation.NonNull;
import android.util.Log;

import com.google.android.gms.auth.api.Auth;
import com.google.android.gms.common.api.GoogleApiClient;
import com.google.android.gms.common.api.ResultCallback;
import com.google.android.gms.common.api.Status;

import java.util.ArrayList;

import com.aplikasisertifikasi.asesor.lspabi.Login.Login;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.SertifikasiRepository;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;

public class ProfilePresenter implements ProfileContract.Presenter {
    private ProfileContract.View view;
    UserRepository userRepository = new UserRepository();
    SertifikasiRepository sertifikasiRepository = new SertifikasiRepository();
    Context context;

    public ProfilePresenter(ProfileContract.View view, Context context) {
        this.view = view;
        this.context = context;
    }

    public void logout() {
        userRepository.logout(new CallbackListener<SinglePayloadResponse>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse singlePayloadResponse) {
                LSPUtils.logout();
                view.logout(Login.class);
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }

    @Override
    public void execute(Object o) {

    }

    @Override
    public void onPause() {

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
        userRepository.getProfile(new CallbackListener<SinglePayloadResponse<Profile>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<Profile> profileSinglePayloadResponse) {
                if (profileSinglePayloadResponse.getResponseStatus().equals("SUCCESS")) {
                    view.setContent(profileSinglePayloadResponse.getPayload());
                } else {
                    view.showSnackBar(context.getString(R.string.load_failed));
                }
            }

            @Override
            public void onError(Throwable throwable) {
                throwable.printStackTrace();
            }
        });
    }

    @Override
    public void getAccessorSkill() {
        view.showLoadingView();
        view.setAccessorSkill(new ArrayList<>());

        sertifikasiRepository.getAccessorSkills(new CallbackListener<DataPayloadListResponse<AccessorCompetence>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<AccessorCompetence> accessorCompetenceDataPayloadListResponse) {
                view.dismissLoadingView();
                view.setAccessorSkill(accessorCompetenceDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }
}
