package com.aplikasisertifikasi.asesor.lspabi.MVPCore;

/**
 * Created by hyperpedia.d on 24/02/2018.
 */

public interface ExtraView extends BaseView {

    void showLoadingView();
    void dismissLoadingView();
    void errorLoadingView();

}
