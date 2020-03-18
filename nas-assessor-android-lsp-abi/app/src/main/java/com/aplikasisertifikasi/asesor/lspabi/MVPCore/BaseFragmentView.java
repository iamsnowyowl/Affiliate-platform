package com.aplikasisertifikasi.asesor.lspabi.MVPCore;

import android.support.v4.app.Fragment;

/**
 * Created by hyperpedia.d on 23/01/2018.
 */

public interface BaseFragmentView extends BaseView {
    void doFragmentInteraction();
    void doFragmentChange(Fragment fragment);
}
