package com.aplikasisertifikasi.asesor.lspabi.MVPCore;

/**
 * Created by hyperpedia.d on 17/01/2018.
 */

public interface BasePresenter<V> {

    void load(V v);
    void start();
    void end();

}
