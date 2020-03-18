package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat.DetailSuratMenyurat;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;

public interface DetailSuratMenyuratContract {
    interface View extends ExtraView{
        void assignCompleted();
    }
    interface Presenter extends BasePresenter{
        void assignSignature(String assessmentId, String letterId, AssessmentLetters assessmentLetters);
    }
}
