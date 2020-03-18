package com.aplikasisertifikasi.asesor.lspabi.MVPCore;

/**
 * Created by hyperpedia.d on 23/01/2018.
 */

public interface BaseFragmentPresenter<V> extends BasePresenter {
    void execute(V v);
    void onPause();
}
