package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat;

import java.util.ArrayList;

import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.Model.DataPayloadListResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class SuratMenyuratPresenter implements SuratMenyuratContract.Presenter {
    SuratMenyuratContract.View view;
    AssessmentRepository assessmentRepository = new AssessmentRepository();

    public SuratMenyuratPresenter(SuratMenyuratContract.View view) {
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
    public void getListSuratMenyurat(String assessmentId) {
        view.showLoadingView();
        view.setSuratToAdapter(new ArrayList<>());
        assessmentRepository.getAssessmentLetters(assessmentId, new CallbackListener<DataPayloadListResponse<AssessmentLetters>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(DataPayloadListResponse<AssessmentLetters> assessmentLettersDataPayloadListResponse) {
                view.dismissLoadingView();
                view.setSuratToAdapter(assessmentLettersDataPayloadListResponse.getPayloadList());
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }
}
