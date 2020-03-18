package com.aplikasisertifikasi.asesor.lspabi.Retrofit.interceptor;

import android.content.Intent;

import org.greenrobot.eventbus.EventBus;

import java.io.IOException;

import com.aplikasisertifikasi.asesor.lspabi.Core.LSPApplication;
import com.aplikasisertifikasi.asesor.lspabi.Login.Login;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;

import okhttp3.Interceptor;
import okhttp3.Request;
import okhttp3.Response;

public class ResponseHeaderInterceptor implements Interceptor {

    @Override
    public Response intercept(Chain chain) throws IOException {
        Request request = chain.request();
        Response response = chain.proceed(request);
        ResponseMessage event = new ResponseMessage();
        Intent intent;

        switch (response.code()) {
//            case 201:
//                event.setResponseCode(response.code());
//                event.setResponseMessage("success");
//                EventBus.getDefault().post(event);
//                break;
            case 400:
                event.setResponseCode(response.code());
                event.setResponseMessage("Form isian salah");
                EventBus.getDefault().post(event);
                break;
            case 401:
                LSPUtils.logout();
                intent = new Intent(LSPApplication.getAppContext(), Login.class);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
                intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TASK);
                LSPApplication.getAppContext().startActivity(intent);
                break;
            case 403:
//                event.setResponseCode(response.code());
//                event.setResponseMessage(response.body().toString());
//                EventBus.getDefault().post(event);
                break;
            case 404:
//                event.setResponseCode(response.code());
//                event.setResponseMessage(response.body().toString());
//                EventBus.getDefault().post(event);
                break;
            case 405:
//                event.setResponseCode(response.code());
//                event.setResponseMessage(response.body().toString());
//                EventBus.getDefault().post(event);
                break;
            case 409:
                event.setResponseCode(response.code());
                event.setResponseMessage("Data already exist");
                EventBus.getDefault().post(event);
                break;
            case 419:
                event.setResponseCode(response.code());
                event.setResponseMessage("Masa free trial anda sudah habis, silakan hubungi Customer Services NAS.");
                EventBus.getDefault().post(event);
                break;
            case 422:
                event.setResponseCode(response.code());
                event.setResponseMessage("Wrong username or password. Try again.");
                EventBus.getDefault().post(event);
                break;
            case 500:
//                event.setResponseCode(response.code());
//                event.setResponseMessage(response.body().toString());
//                EventBus.getDefault().post(event);
                break;
        }

        return response;
    }
}
