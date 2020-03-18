package com.aplikasisertifikasi.asesor.lspabi.Login;

import android.content.Intent;
import android.os.Bundle;
import android.provider.Settings;
import android.support.annotation.NonNull;
import android.support.design.widget.Snackbar;
import android.view.View;
import android.widget.EditText;
import android.widget.ScrollView;
import android.widget.Toast;

import com.google.android.gms.common.ConnectionResult;
import com.google.android.gms.common.api.GoogleApiClient;

import org.greenrobot.eventbus.Subscribe;

import butterknife.BindView;
import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;
import com.aplikasisertifikasi.asesor.lspabi.ForgotPassword.ForgotPassword;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Signup.Signup;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;


public class Login extends BaseActivity implements LoginContract.View, GoogleApiClient.OnConnectionFailedListener {

    private LoginPresenter presenter = new LoginPresenter(this, this);
    @BindView(R.id.input_username)
    EditText username;
    @BindView(R.id.input_password)
    EditText password;
    @BindView(R.id.scrollview)
    ScrollView scrollView;
    //    @BindView(R.id.btn_login_google_primary)
//    SignInButton loginButtonGoogle;
//    @BindView(R.id.btn_login_fb_primary)
//    LoginButton loginButtonFB;
    String serialNumber;

    //    CallbackManager callbackManager;
    //    GoogleApiClient googleApiClient;
//    GoogleSignInOptions googleSignInOptions;
//    GoogleSignInClient googleSignInClient;
//    GoogleSignInAccount googleSignInAccount;
    private static final int RC_GOOGLE_SIGN_IN = 03;
    LSPUtils lspUtils;


    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        lspUtils = new LSPUtils();
        presenter.start();
    }

    @Override
    protected int getLayoutId() {
//        FacebookSdk.sdkInitialize(getApplicationContext());
        return R.layout.activity_login;
    }

    @Subscribe
    public void onEvent(ResponseMessage event) {
        Snackbar.make(scrollView, event.getResponseMessage(), Snackbar.LENGTH_SHORT).show();
    }

    @Override
    protected void onActivityResult(int requestCode, int resultCode, Intent data) {
        super.onActivityResult(requestCode, resultCode, data);

//        if (requestCode == RC_GOOGLE_SIGN_IN) {
//            Task<GoogleSignInAccount> task = GoogleSignIn.getSignedInAccountFromIntent(data);
//            try {
//                googleSignInAccount = task.getResult(ApiException.class);
//                setSharePrefRegister(googleSignInAccount.getDisplayName().split(" ")[0], googleSignInAccount.getDisplayName().split(" ")[1], googleSignInAccount.getEmail());
//            } catch (ApiException e) {
//                e.printStackTrace();
//            }
//        }

//        callbackManager.onActivityResult(requestCode, resultCode, data);
    }

    @OnClick(value = {
            R.id.btnLogin,
//            R.id.btn_login_google,
//            R.id.btn_login_fb
    })
    public void onLoginClicked(View view) {
        if (view.getId() == R.id.btnLogin)
            if (presenter.loginValidate(username, password)) {
                presenter.authLogin(username.getText().toString(), password.getText().toString(), this);
            }
//        if (view.getId() == R.id.btn_login_google) {
//            googleSignInClient.signOut();
//            LSPUtils.logout();
//
//            Intent googleInten = googleSignInClient.getSignInIntent();
//            startActivityForResult(googleInten, RC_GOOGLE_SIGN_IN);
//        } else if (view.getId() == R.id.btn_login_fb) {
//            LoginManager.getInstance().logOut();
//            LSPUtils.logout();
//            loginButtonFB.performClick();
//        } else {
//            if (presenter.loginValidate(username, password)) {
//                presenter.authLogin(username.getText().toString(), password.getText().toString(), this);
//            }
//        }
    }


    @OnClick(R.id.btnForgotPass)
    public void onBtnForgotClick() {
        Intent i = new Intent(Login.this, ForgotPassword.class);
        startActivity(i);
    }

    @OnClick(R.id.btnSignUp)
    public void onBtnSignUpClick() {
        Intent i = new Intent(Login.this, Signup.class);
        LSPUtils.logout();
        startActivity(i);
    }

    @Override
    public void initViews() {
        //google
//        googleSignInOptions = new GoogleSignInOptions.Builder(GoogleSignInOptions.DEFAULT_SIGN_IN)
//                .requestEmail()
//                .build();
//        googleApiClient = new GoogleApiClient.Builder(this)
//                .addApi(Auth.GOOGLE_SIGN_IN_API, googleSignInOptions)
//                .build();
//        googleSignInClient = GoogleSignIn.getClient(this, googleSignInOptions);
//
//        //facebook
//        callbackManager = CallbackManager.Factory.create();
//        FacebookCallback<LoginResult> callback = new FacebookCallback<LoginResult>() {
//            @Override
//            public void onSuccess(LoginResult loginResult) {
//                LSPUtils.setString(FacebookEntity.FB_APP_ID, AccessToken.getCurrentAccessToken().getApplicationId());
//                LSPUtils.setString(FacebookEntity.FB_TOKEN, AccessToken.getCurrentAccessToken().getToken());
//                LSPUtils.setString(FacebookEntity.FB_USER_ID, AccessToken.getCurrentAccessToken().getUserId());
//
//                Log.d("register-fb", "Success Register Facebook");
//                GraphRequest request = GraphRequest.newMeRequest(loginResult.getAccessToken(), (object, response) -> {
//                    try {
//                        setSharePrefRegister(object.getString("first_name"), object.getString("last_name"), object.getString("email"));
//                    } catch (JSONException e) {
//                        e.printStackTrace();
//                    }
//
//                });
//
//                Bundle params = new Bundle();
//                params.putString("fields", "id,first_name,last_name,email");
//                request.setParameters(params);
//                request.executeAsync();
//            }
//
//            @Override
//            public void onCancel() {
//                Log.d("register-fb", "Cancel Register Facebook");
//            }
//
//            @Override
//            public void onError(FacebookException error) {
//                Log.d("register-fb", "Error register with Facebook " + error.getMessage());
//                LoginManager.getInstance().logOut();
//                LSPUtils.logout();
//            }
//        };
//
//        loginButtonFB.setReadPermissions(Arrays.asList("public_profile", "email"));
//        loginButtonFB.registerCallback(callbackManager, callback);

        serialNumber = Settings.Secure.getString(this.getContentResolver(), Settings.Secure.ANDROID_ID);
    }

    @Override
    public void showSnackBar(String message) {
        Snackbar.make(scrollView, message, Snackbar.LENGTH_SHORT).show();
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_LONG).show();
    }

    @Override
    public void showLoadingView() {
        ProgressLoadingBar.show(this);
    }

    @Override
    public void dismissLoadingView() {
        ProgressLoadingBar.dismiss();
    }

    @Override
    public void errorLoadingView() {
    }

    @Override
    public void startActivity(Class<?> c) {
        startActivity(new Intent(this, c));
        finish();
    }

    @Override
    public void onConnectionFailed(@NonNull ConnectionResult connectionResult) {

    }

    private void setSharePrefRegister(String firstName, String lastName, String email) {
        LSPUtils.setString(AsessorEntity.USER_FIRST_NAME, firstName);
        LSPUtils.setString(AsessorEntity.USER_LAST_NAME, lastName);
        LSPUtils.setString(AsessorEntity.USER_EMAIL, email);

        startActivity(new Intent(this, Signup.class));
    }
}
