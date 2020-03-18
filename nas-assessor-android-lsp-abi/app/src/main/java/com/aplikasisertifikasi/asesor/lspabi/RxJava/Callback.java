package com.aplikasisertifikasi.asesor.lspabi.RxJava;

/**
 * Created by hyperpedia.d on 20/02/2018.
 */

public interface Callback<T> {

    void onCompleted();
    void onCompleted(T t);
    void onError(Throwable e);

}
