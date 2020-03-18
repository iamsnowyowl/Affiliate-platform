package com.aplikasisertifikasi.asesor.lspabi.Retrofit.interceptor;

import java.io.IOException;

import okhttp3.Interceptor;
import okhttp3.Response;

import static com.aplikasisertifikasi.asesor.lspabi.Config.Config.AUTHORIZATION;

public class RequestHeaderInterceptor implements Interceptor {

    private String[] authorizations;

    public RequestHeaderInterceptor(String... authorizations) {
        this.authorizations = authorizations;
    }

    @Override
    public Response intercept(Chain chain) throws IOException {


        return chain.proceed(chain.request().newBuilder()
                .addHeader(AUTHORIZATION, "Bearer ")
                .build());
    }

}
