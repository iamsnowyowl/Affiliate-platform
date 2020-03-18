package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragmentPresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.Profile;

public interface ProfileContract{

    interface View extends ExtraView {
        void showSnackBar(String message);
        void setContent(Profile profile);
        void setAccessorSkill(List<AccessorCompetence> listAccessorSkill);
        void logout(Class c);
    }

    interface Presenter extends BaseFragmentPresenter {
        void getProfile();
        void getAccessorSkill();
    }
}
