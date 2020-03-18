package com.aplikasisertifikasi.asesor.lspabi.ForgotPassword;

import android.content.Context;
import android.text.TextUtils;
import android.util.Patterns;
import android.widget.EditText;

import com.aplikasisertifikasi.asesor.lspabi.Model.ForgotPassModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;

public class ForgotPassPresenter implements ForgotPassContract.Presenter {

    private ForgotPassContract.View view;
    UserRepository userRepository = new UserRepository();
    Context context;

    public ForgotPassPresenter(ForgotPassContract.View view, Context context) {
        this.view = view;
        this.context = context;
    }

    public boolean validate(EditText forgotPass) {
        if (TextUtils.isEmpty(forgotPass.getText().toString())) {
            forgotPass.setError(context.getResources().getString(R.string.empty_email));
            return false;
        }
        if (!Patterns.EMAIL_ADDRESS.matcher(forgotPass.getText().toString()).matches()) {
            view.showSnackbar(context.getResources().getString(R.string.email_validation));
            return false;
        }
        return true;
    }

    @Override
    public void sendEmail(String email) {
        userRepository.forgotPassword(new ForgotPassModel(email), new CallbackListener<SinglePayloadResponse<ForgotPassModel>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse<ForgotPassModel> forgotPassModelSinglePayloadResponse) {
                if (forgotPassModelSinglePayloadResponse.getResponseStatus().equals("SUCCESS")) {
                    view.showMaterialDialog(context.getResources().getString(R.string.success_reset_password));
                }
            }

            @Override
            public void onError(Throwable throwable) {

            }
        });
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
}
