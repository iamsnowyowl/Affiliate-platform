package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivityFragmentView;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;

public interface DetailHomeContract {
    interface View extends BaseActivityFragmentView {
        void showLoadingView();
        void dismissLoadingView();
        void errorLoadingView();
        void determineFragment();
    }

    interface Presenter extends BasePresenter {
        void asessmenProcess(String process);
    }
}
