package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;

public interface SuratMenyuratContract {
    interface View extends ExtraView {
        void setSuratToAdapter(List<AssessmentLetters> assessmentLetters);
    }

    interface Presenter extends BasePresenter {
        void getListSuratMenyurat(String assessmentId);
    }
}
