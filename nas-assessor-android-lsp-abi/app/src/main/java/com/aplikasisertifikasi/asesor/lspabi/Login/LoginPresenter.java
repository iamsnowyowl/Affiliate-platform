package com.aplikasisertifikasi.asesor.lspabi.Login;

import android.content.Context;
import android.text.TextUtils;
import android.util.Log;
import android.widget.EditText;

import com.google.firebase.FirebaseApp;
import com.google.firebase.auth.FacebookAuthProvider;
import com.google.firebase.auth.GoogleAuthProvider;
import com.google.firebase.iid.FirebaseInstanceId;

import com.aplikasisertifikasi.asesor.lspabi.Core.LSPApplication;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.MainActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.Authentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.LoginResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Preference.FirebaseUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.UserRepository;

import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.schedulers.Schedulers;

public class LoginPresenter implements LoginContract.Presenter {

    LoginContract.View view;
    UserRepository userRepository = new UserRepository();
    Context context;

    public LoginPresenter(LoginContract.View view, Context context) {
        this.view = view;
        this.context = context;
    }

    public boolean loginValidate(EditText username, EditText password) {
        if (TextUtils.isEmpty(username.getText().toString())) {
            username.setError(context.getString(R.string.empty_username));

            return false;
        }
        if (TextUtils.isEmpty(password.getText().toString())) {
            password.setError(context.getString(R.string.empty_password));

            return false;
        }
        if (password.getText().toString().length() < 6) {
            password.setError(context.getString(R.string.password_min_6char));

            return false;
        }

        return true;
    }

    @Override
    public void authLogin(String username, String password, Context context) {
//        FirebaseApp.initializeApp(context);
        view.showLoadingView();
        userRepository.auth(new Authentication(username, password), new CallbackListener<LoginResponse<Profile>>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(LoginResponse<Profile> apiResponseLoginResponse) {
                view.dismissLoadingView();
                if (apiResponseLoginResponse.getV().getRoleCode().equals("ACS") ||
                        apiResponseLoginResponse.getV().getRoleCode().equals("MAG") ||
                        apiResponseLoginResponse.getV().getRoleCode().equals("SUP")) {
                    sendFCMToken(FirebaseInstanceId.getInstance().getToken());
                    view.startActivity(MainActivity.class);
                } else {
                    view.showSnackBar("Maaf anda tidak dapat mengakses aplikasi ini");
                }
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
            }
        });
    }

    @Override
    public void sendFCMToken(String fcmToken) {
        userRepository.updateToken(fcmToken, new CallbackListener<SinglePayloadResponse>() {
            @Override
            public void onCompleted() {

            }

            @Override
            public void onCompleted(SinglePayloadResponse singlePayloadResponse) {
                Log.d("SEND FDM", "SUCCESS");
            }

            @Override
            public void onError(Throwable throwable) {
                Log.d("SEND FDM", "ERROR");
            }
        });
    }

//    @Override
//    public void firebaseLoginFacebookCredentials(AccessToken accessToken) {
//        view.showLoadingView();
//
//        RxFirebaseAuth.signInWithCredential(FirebaseUtils.getFirebaseAuth(), FacebookAuthProvider.getCredential(accessToken.getToken()))
//                .observeOn(Schedulers.newThread())
//                .subscribeOn(AndroidSchedulers.mainThread())
//                .subscribe(authResult -> {
//                    // Sign in success, update UI with the signed-in user's information
//                    Log.d("Sign Sukses", "signInWithCredential:success" + accessToken.getToken());
//
//                    view.dismissLoadingView();
//                    view.startActivity(MainActivity.class);
//                }, throwable -> {
//                    throwable.printStackTrace();
//
//                    view.dismissLoadingView();
//                    view.showSnackBar("Gagal login dengan Facebook");
//                });
//    }

//    @Override
//    public void firebaseLoginGoogleCredential(GoogleSignInAccount googleSignInAccount) {
//        view.showLoadingView();
//        RxFirebaseAuth.signInWithCredential(FirebaseUtils.getFirebaseAuth(), GoogleAuthProvider.getCredential(googleSignInAccount.getIdToken(), null))
//                .observeOn(io.reactivex.schedulers.Schedulers.newThread())
//                .subscribeOn(AndroidSchedulers.mainThread())
//                .subscribe(authResult -> {
//                    Log.d("Sign In Success", "googleSignInAccount:success");
//
//                    view.dismissLoadingView();
////                    LSPUtils.setSecretKey(googleSignInAccount.getIdToken());
////                    LSPUtils.setName(googleSignInAccount.getDisplayName());
////                    LSPUtils.setUsernameEmail(googleSignInAccount.getEmail());
////                    view.startActivity(MainActivity.class);
//                }, throwable -> {
//                    throwable.printStackTrace();
//
//                    view.dismissLoadingView();
//                    view.showSnackBar("Gagal login dengan google");
//                });

//    }

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
