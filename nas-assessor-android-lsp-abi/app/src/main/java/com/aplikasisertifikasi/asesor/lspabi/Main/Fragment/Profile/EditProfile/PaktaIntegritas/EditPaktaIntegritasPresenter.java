package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile.PaktaIntegritas;

import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;

public class EditPaktaIntegritasPresenter implements EditPaktaIntegritasContract.Presenter {

    private EditPaktaIntegritasContract.View view;
    UserRepository userRepository = new UserRepository();

    EditPaktaIntegritasPresenter(EditPaktaIntegritasContract.View view) {
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
    public void assignIntegrityPact(Profile profile) {
        view.showLoadingView();
        userRepository.assignIntegrity(profile, new CallbackListener<Profile>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(Profile profile) {
                view.dismissLoadingView();
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
    }
}
