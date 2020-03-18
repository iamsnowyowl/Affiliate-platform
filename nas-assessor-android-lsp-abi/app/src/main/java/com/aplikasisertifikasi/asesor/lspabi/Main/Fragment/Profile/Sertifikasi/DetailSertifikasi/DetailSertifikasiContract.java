package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.DetailSertifikasi;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BasePresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;

public interface DetailSertifikasiContract {
    interface View extends ExtraView{
        void setDetailSkill(AccessorCompetence accessorCompetence);
    }
    interface Presenter extends BasePresenter{
        void getDetailSkill(String id_accessor_skill);
    }
}
