package com.aplikasisertifikasi.asesor.lspabi.Listener;

import android.view.View;

/**
 * Created by hyperpedia.d on 22/01/2018.
 */

public interface RecyclerViewListener<T> {

    void onItemChooseCallback(View view, T t, int position);

}
