package com.aplikasisertifikasi.asesor.lspabi.Retrofit;

public interface CallbackListener<T> {
    void onCompleted();

    void onCompleted(T t);

    void onError(Throwable throwable);
}
