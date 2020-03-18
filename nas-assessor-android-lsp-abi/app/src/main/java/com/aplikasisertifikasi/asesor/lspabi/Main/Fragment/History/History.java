package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.History;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.TextView;

import com.victor.loading.rotate.RotateLoading;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.HistoryAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class History extends Fragment implements HistoryContract.View {

    @BindView(R.id.recyclerHistory)
    RecyclerView recyclerView;
    @BindView(R.id.no_task)
    LinearLayout noTaskLayout;
    @BindView(R.id.img_empty_history)
    ImageView imgEmptyHistory;
    @BindView(R.id.txt_empty_history)
    TextView txtEmptyHistory;
    @BindView(R.id.error_container)
    LinearLayout errorContainer;
    @BindView(R.id.try_again_button)
    Button tryAgainButton;
    @BindView(R.id.task_history)
    TextView titleFragment;
    @BindView(R.id.rotate_loading)
    RotateLoading rotateLoading;

    HistoryAdapter adapter;
    HistoryPresenter presenter = new HistoryPresenter(this);
    private List<AssessmentSchedule> historyList = new ArrayList<>();

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_history, container, false);
        ButterKnife.bind(this, view);

        presenter.start();
        return view;
    }

    @Override
    public void startActivity(Class c) {
        startActivity(new Intent(getContext(), c));
    }

    @Override
    public void initViews() {

    }

    @Override
    public void onResume() {
        super.onResume();
        presenter.getReportAssessment();
    }

    @Override
    public void showLoadingView() {
        rotateLoading.start();
    }

    @Override
    public void dismissLoadingView() {
        rotateLoading.stop();
    }

    @Override
    public void errorLoadingView() {
        errorContainer.setVisibility(View.VISIBLE);
        recyclerView.setVisibility(View.GONE);
        tryAgainButton.setOnClickListener(v -> {
            errorContainer.setVisibility(View.GONE);
            presenter.getReportAssessment();
        });
    }

    @Override
    public void setReportAssessment(List<AssessmentSchedule> assessments) {
        LinearLayoutManager llm = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        adapter = new HistoryAdapter(assessments);
        recyclerView.setAdapter(adapter);
        recyclerView.setLayoutManager(llm);

        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
            titleFragment.setText(R.string.report_manager);
            if (assessments.size() < 1) {
                errorContainer.setVisibility(View.GONE);
                recyclerView.setVisibility(View.GONE);
                noTaskLayout.setVisibility(View.VISIBLE);
                imgEmptyHistory.setImageResource(R.drawable.empty);
                txtEmptyHistory.setText(R.string.empty_report);
            } else {
                errorContainer.setVisibility(View.GONE);
                recyclerView.setVisibility(View.VISIBLE);
                noTaskLayout.setVisibility(View.GONE);
            }
        } else {
            titleFragment.setText(R.string.task_report);
            if (assessments.size() < 1) {
                errorContainer.setVisibility(View.GONE);
                recyclerView.setVisibility(View.GONE);
                noTaskLayout.setVisibility(View.VISIBLE);
                imgEmptyHistory.setImageResource(R.drawable.empty);
                txtEmptyHistory.setText(R.string.no_completed_task);
            } else {
                errorContainer.setVisibility(View.GONE);
                recyclerView.setVisibility(View.VISIBLE);
                noTaskLayout.setVisibility(View.GONE);
            }
        }
    }
}
