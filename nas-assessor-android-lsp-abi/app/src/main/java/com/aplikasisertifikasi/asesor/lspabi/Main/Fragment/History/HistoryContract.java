package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.History;

import java.util.List;

import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseFragmentPresenter;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.ExtraView;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;

public interface HistoryContract {

    interface View extends ExtraView {
        void setReportAssessment(List<AssessmentSchedule> assessments);
    }
    interface Presenter extends BaseFragmentPresenter {
        void getReportAssessment();
    }
}
