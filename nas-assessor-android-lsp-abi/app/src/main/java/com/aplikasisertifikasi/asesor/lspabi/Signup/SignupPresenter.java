package com.aplikasisertifikasi.asesor.lspabi.Signup;

import android.content.Context;
import android.text.TextUtils;
import android.util.Patterns;
import android.widget.EditText;
import android.widget.RadioGroup;

import com.aplikasisertifikasi.asesor.lspabi.Model.SignUp;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;

public class SignupPresenter implements SignupContract.Presenter {
    private SignupContract.View view;
    UserRepository userRepository = new UserRepository();
    Context context;

    public SignupPresenter(SignupContract.View view, Context context) {
        this.view = view;
        this.context = context;
    }

    private boolean isMatch(String password, String confirmPassword) {
        return password.equals(confirmPassword);
    }

    public boolean validateSignup(EditText username, EditText certificateNumber, EditText email, EditText firstName, EditText contact, String gender) {
        if (TextUtils.isEmpty(username.getText().toString()) || TextUtils.isEmpty(certificateNumber.getText().toString()) || TextUtils.isEmpty(email.getText().toString())
                || TextUtils.isEmpty(firstName.getText().toString()) || TextUtils.isEmpty(contact.getText().toString()) || gender.equals("")) {
            view.showSnackBar(context.getString(R.string.data_not_completed));
            return false;
        }
        if (!Patterns.EMAIL_ADDRESS.matcher(email.getText().toString()).matches()) {
            view.showSnackBar(context.getString(R.string.email_validation));
            return false;
        }
        return true;
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
    public void createAccount(String username, String registrtionNumber, String email, String firstName, String lastName, String contact, String gender, String dateOfBirth, String placeOfBirth, String signature) {
        view.showLoadingView();
        userRepository.createAccount(new SignUp(username, registrtionNumber, email, firstName, lastName, gender, contact, dateOfBirth, placeOfBirth, signature), new CallbackListener<SinglePayloadResponse<SignUp>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<SignUp> signUpSinglePayloadResponse) {
                if (signUpSinglePayloadResponse.getResponseStatus().equals("SUCCESS")) {
                    view.dismissLoadingView();
                    view.showDialog(context.getString(R.string.success), context.getString(R.string.check_email_for_password));
                }
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
            }
        });
    }
}
