package com.aplikasisertifikasi.asesor.lspabi.Api;

import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Model.Authentication;
import com.aplikasisertifikasi.asesor.lspabi.Model.ForgotPassModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.LoginResponse;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;
import com.aplikasisertifikasi.asesor.lspabi.Model.SignUp;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import io.reactivex.Observable;
import retrofit2.http.Body;
import retrofit2.http.Header;
import retrofit2.http.POST;
import retrofit2.http.Path;

public interface UserServices {

    interface POST {

        @retrofit2.http.POST("users/login")
        Observable<LoginResponse<Profile>> auth(@Body Authentication authentication);

        @retrofit2.http.POST("public/users/accessors")
        Observable<SinglePayloadResponse<SignUp>> createAccount(@Body SignUp signUp);

        @retrofit2.http.POST("public/users/forgot_password")
        Observable<SinglePayloadResponse<ForgotPassModel>> forgotPassword(@Body ForgotPassModel forgotPassword);

        @retrofit2.http.POST("users/logout")
        Observable<SinglePayloadResponse> logout(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date
        );
    }

    interface GET {
        @retrofit2.http.GET("me")
        Observable<SinglePayloadResponse<Profile>> getUserProfile(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date
        );
    }

    interface PUT {
        @retrofit2.http.PUT("me/picture")
        Observable<Profile> updatePicture(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Body Profile profile
        );

        @retrofit2.http.PUT("me")
        Observable<Profile> updateProfileAccessor(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Body Profile profile
        );

        @retrofit2.http.PUT("me/refresh_token/{fcm_token}")
        Observable<SinglePayloadResponse> updateFCMToken(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Path("fcm_token") String fcm_token
        );

        @retrofit2.http.PUT("me/documents/integrity_pact/generate_pdf")
        Observable<Profile> assignIntegrity(
                @Header(Config.AUTHORIZATION) String auth,
                @Header(Config.DIGEST_DATE) String date,
                @Body Profile profile
        );
    }

}

