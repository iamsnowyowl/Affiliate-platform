package com.aplikasisertifikasi.asesor.lspabi.RxJava;

import android.util.Log;

import java.util.ArrayList;
import java.util.HashMap;

import com.aplikasisertifikasi.asesor.lspabi.Api.UserServices;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;
import com.aplikasisertifikasi.asesor.lspabi.Model.Authentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.DigestAuthentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.ForgotPassModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.LoginResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Model.SignUp;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.UserPermission;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.RetrofitClient;
import com.aplikasisertifikasi.asesor.lspabi.Utils.DigestHelper;

import io.reactivex.Observable;
import io.reactivex.ObservableSource;
import io.reactivex.android.schedulers.AndroidSchedulers;
import io.reactivex.disposables.Disposable;
import io.reactivex.functions.Consumer;
import io.reactivex.functions.Function;
import io.reactivex.schedulers.Schedulers;

public class UserRepository {

    UserServices.POST userServicesPOST = RetrofitClient.getClient().create(UserServices.POST.class);
    UserServices.GET userProfileGET = RetrofitClient.getClient().create(UserServices.GET.class);
    UserServices.PUT updateProfilePUT = RetrofitClient.getClient().create(UserServices.PUT.class);

    public Disposable auth(Authentication authentication, CallbackListener<LoginResponse<Profile>> callbackListener) {
        return userServicesPOST.auth(authentication)
                .concatMap((Function<LoginResponse<Profile>, ObservableSource<LoginResponse<Profile>>>) apiResponseLoginResponse -> {
                    if (apiResponseLoginResponse.getV().getRoleCode().equals("ACS") ||
                            apiResponseLoginResponse.getV().getRoleCode().equals("MAG") ||
                            apiResponseLoginResponse.getV().getRoleCode().equals("SUP")) {
                        LSPUtils.setUsernameEmail(authentication.getUsername_email());
                        LSPUtils.setSecretKey(apiResponseLoginResponse.getSecretKey());
                        LSPUtils.setRoleCode(apiResponseLoginResponse.getV().getRoleCode());
                        LSPUtils.setString(AsessorEntity.USER_ID, apiResponseLoginResponse.getV().getUserId());
                        LSPUtils.setString(AsessorEntity.USER_FULL_NAME, apiResponseLoginResponse.getV().getFirstName() + " " + apiResponseLoginResponse.getV().getLastName());
                        LSPUtils.setString(AsessorEntity.USER_EMAIL, apiResponseLoginResponse.getV().getEmail());
                        LSPUtils.setString(AsessorEntity.USER_ADDRESS, apiResponseLoginResponse.getV().getAddress());
                        LSPUtils.setString(AsessorEntity.USER_CONTACT, apiResponseLoginResponse.getV().getContact());
                        LSPUtils.setString(AsessorEntity.USER_DATE_BIRTH, apiResponseLoginResponse.getV().getDateOfBirth());
                        LSPUtils.setString(AsessorEntity.USER_PLACE_BIRTH, apiResponseLoginResponse.getV().getPlaceOfBirth());
                    }

                    return Observable.just(apiResponseLoginResponse);
                })
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(apiResponseLoginResponse -> {
                    callbackListener.onCompleted(apiResponseLoginResponse);
                }, throwable -> callbackListener.onError(throwable));
    }

    public Disposable forgotPassword(ForgotPassModel forgotPassModel, CallbackListener<SinglePayloadResponse<ForgotPassModel>> callbackListener) {
        return userServicesPOST.forgotPassword(forgotPassModel)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(forgotPassModelSinglePayloadResponse -> callbackListener.onCompleted(forgotPassModelSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable createAccount(SignUp signUp, CallbackListener<SinglePayloadResponse<SignUp>> callbackListener) {
        return userServicesPOST.createAccount(signUp)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(signUpSinglePayloadResponse -> callbackListener.onCompleted(signUpSinglePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable getProfile(CallbackListener<SinglePayloadResponse<Profile>> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("GET", "/me");
        return userProfileGET.getUserProfile(digestAuthentication.getAuthorization(), digestAuthentication.getDate())
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe((SinglePayloadResponse<Profile> profileSinglePayloadResponse) -> callbackListener.onCompleted(profileSinglePayloadResponse), throwable -> callbackListener.onError(new Throwable()));
    }

    public Disposable updateImg(Profile profile, CallbackListener<Profile> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/me/picture");
        return updateProfilePUT.updatePicture(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), profile)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(profile1 -> callbackListener.onCompleted(profile1), throwable -> {
                    callbackListener.onError(throwable);
                });
    }

    public Disposable updateProfile(Profile profile, CallbackListener<Profile> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/me");
        return updateProfilePUT.updateProfileAccessor(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), profile)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(profile1 -> callbackListener.onCompleted(profile1), throwable -> callbackListener.onError(throwable));
    }

    public Disposable assignIntegrity(Profile profile, CallbackListener<Profile> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/me/documents/integrity_pact/generate_pdf");
        return updateProfilePUT.assignIntegrity(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), profile)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(profile1 -> callbackListener.onCompleted(profile1), throwable -> callbackListener.onError(throwable));
    }

    public Disposable updateToken(String refreshedToken, CallbackListener<SinglePayloadResponse> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("PUT", "/me/refresh_token/" + refreshedToken);
        return updateProfilePUT.updateFCMToken(digestAuthentication.getAuthorization(), digestAuthentication.getDate(), refreshedToken)
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(singlePayloadResponse -> callbackListener.onCompleted(singlePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

    public Disposable logout(CallbackListener<SinglePayloadResponse> callbackListener) {
        DigestHelper digestHelper = new DigestHelper();
        digestHelper.setUsername(LSPUtils.getUsernameEmail());
        digestHelper.setSecret(LSPUtils.getSecretKey());
        DigestAuthentication digestAuthentication = digestHelper.getDigest("POST", "/users/logout");
        return userServicesPOST.logout(digestAuthentication.getAuthorization(), digestAuthentication.getDate())
                .subscribeOn(Schedulers.newThread())
                .observeOn(AndroidSchedulers.mainThread())
                .subscribe(singlePayloadResponse -> callbackListener.onCompleted(singlePayloadResponse), throwable -> callbackListener.onError(throwable));
    }

}
