package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Profile.Sertifikasi.DetailSertifikasi;

import com.aplikasisertifikasi.asesor.lspabi.Model.AccessorCompetence;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.SertifikasiRepository;

public class DetailSertifikasiPresenter implements DetailSertifikasiContract.Presenter {

    SertifikasiRepository sertifikasiRepository = new SertifikasiRepository();
    private DetailSertifikasiContract.View view;

    public DetailSertifikasiPresenter(DetailSertifikasiContract.View view) {
        this.view = view;
    }

    @Override
    public void load(Object o) {

    }

    @Override
    public void start() {
        view.initViews();
    }

    @Override
    public void end() {

    }

    @Override
    public void getDetailSkill(String id_accessor_skill) {
        view.showLoadingView();
        sertifikasiRepository.getDetailAccessorSkill(id_accessor_skill, new CallbackListener<SinglePayloadResponse<AccessorCompetence>>(){
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<AccessorCompetence> accessorCompetenceSinglePayloadResponse) {
                view.dismissLoadingView();
                view.setDetailSkill(accessorCompetenceSinglePayloadResponse.getPayload());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
            }
        });
    }
}
