package com.aplikasisertifikasi.asesor.lspabi.MVPCore;

import android.support.v4.app.Fragment;

/**
 * Created by hyperpedia.d on 23/01/2018.
 */

public interface BaseActivityFragmentView extends BaseView {
    void changeFragment(Fragment fragment);
    void initFragmentListener();
}
