package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.SuratMenyurat.DetailSuratMenyurat;

import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentLetters;
import com.aplikasisertifikasi.asesor.lspabi.Model.SinglePayloadResponse;
import com.aplikasisertifikasi.asesor.lspabi.Retrofit.CallbackListener;
import com.aplikasisertifikasi.asesor.lspabi.RxJava.AssessmentRepository;

public class DetailSuratMenyuratPresenter implements DetailSuratMenyuratContract.Presenter {
    DetailSuratMenyuratContract.View view;
    AssessmentRepository assessmentRepository = new AssessmentRepository();

    public DetailSuratMenyuratPresenter(DetailSuratMenyuratContract.View view) {
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
    public void assignSignature(String assessmentId, String letterId, AssessmentLetters assessmentLetters) {
        view.showLoadingView();
        assessmentRepository.assignSignature(assessmentLetters, assessmentId, letterId, new CallbackListener<SinglePayloadResponse<AssessmentLetters>>() {
            @Override
            public void onCompleted() {
                view.dismissLoadingView();
            }

            @Override
            public void onCompleted(SinglePayloadResponse<AssessmentLetters> assessmentLettersSinglePayloadResponse) {
                view.dismissLoadingView();
                view.assignCompleted();
            }

            @Override
            public void onError(Throwable throwable) {
                view.dismissLoadingView();
                view.errorLoadingView();
            }
        });
    }
}
