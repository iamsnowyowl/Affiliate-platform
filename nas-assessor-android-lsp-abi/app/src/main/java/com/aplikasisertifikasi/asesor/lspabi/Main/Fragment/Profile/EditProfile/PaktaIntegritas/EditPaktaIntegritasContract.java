package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.EditProfile.PaktaIntegritas;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;

public interface EditPaktaIntegritasContract {
    interface View extends ExtraView {

    }

    interface Presenter extends BasePresenter {
        void assignIntegrityPact(Profile profile);
    }
}
