package com.aplikasisertifikasi.asesor.lspabi.Retrofit;

import java.util.concurrent.TimeUnit;

import com.aplikasisertifikasi.asesor.lspabi.BuildConfig;
import com.aplikasisertifikasi.asesor.lspabi.Config.Config;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.interceptor.ResponseHeaderInterceptor;
import okhttp3.OkHttpClient;
import okhttp3.logging.HttpLoggingInterceptor;
import retrofit2.Retrofit;
import retrofit2.adapter.rxjava2.RxJava2CallAdapterFactory;
import retrofit2.converter.gson.GsonConverterFactory;

public class RetrofitClient {
    private static Retrofit retrofit;

//    public static Retrofit getClient(String... baseUrl) {
    public static Retrofit getClient() {
        if (retrofit == null) {
            HttpLoggingInterceptor logging = new HttpLoggingInterceptor();
            OkHttpClient.Builder httpClient = new OkHttpClient.Builder();

            if (BuildConfig.BUILD_TYPE != "release"){
            logging.setLevel(HttpLoggingInterceptor.Level.BODY);
            httpClient.addInterceptor(logging);
            }
            httpClient.addInterceptor(new ResponseHeaderInterceptor());
            httpClient.readTimeout(10, TimeUnit.SECONDS);
            httpClient.connectTimeout(10, TimeUnit.SECONDS);
//            httpClient.addInterceptor(new RequestHeaderInterceptor());

            retrofit = new Retrofit.Builder()
//                    .baseUrl(baseUrl.length > 0 ? baseUrl[0] : Config.BASE_URL_DEMO)
                    .baseUrl(BuildConfig.BASE_URL)
                    .addConverterFactory(GsonConverterFactory.create())
                    .addCallAdapterFactory(RxJava2CallAdapterFactory.create())
                    .client(httpClient.build())
                    .build();
        }

        return retrofit;
    }
}
