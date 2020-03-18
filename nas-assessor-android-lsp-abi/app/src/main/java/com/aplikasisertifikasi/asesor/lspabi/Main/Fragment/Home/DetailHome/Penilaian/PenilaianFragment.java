package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home.DetailHome.Penilaian;

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
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.ApplicantsAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AsessorEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.AssessmentEntity;
import com.aplikasisertifikasi.asesor.lspabi.Entity.PaginationEntity;
import com.aplikasisertifikasi.asesor.lspabi.Model.Applicant;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;

import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

import com.aplikasisertifikasi.asesor.lspabi.Utils.Pagination.PaginationRecyclerViewListener;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;

public class PenilaianFragment extends Fragment implements PenilaianContract.View {

    private static PenilaianFragment penilaianFragment;
    private PenilaianPresenter presenter = new PenilaianPresenter(this, getContext());
    private ApplicantsAdapter adapter;
    @BindView(R.id.recyclerStep2)
    RecyclerView recyclerView;
    @BindView(R.id.empty_container)
    LinearLayout emptyContainer;
    @BindView(R.id.try_again_button)
    Button tryAgainButton;
    @BindView(R.id.error_container)
    LinearLayout errorContainer;
    @BindView(R.id.img_empty)
    ImageView imgEmpty;
    @BindView(R.id.txt_empty)
    TextView txtEmpty;
    @BindView(R.id.next_page_loading)
    ProgressBar loading;
    String assessmentId;
    LinearLayoutManager llm;
    int currentApplicantPosition;

    public PenilaianFragment() {
    }

    public static PenilaianFragment getInstance() {
        if (penilaianFragment == null) {
            penilaianFragment = new PenilaianFragment();
        }
        return penilaianFragment;
    }

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View view = inflater.inflate(R.layout.fragment_penilaian, container, false);
        ButterKnife.bind(this, view);
        presenter.start();

        Bundle bundle = this.getArguments();
        if (bundle != null)
            assessmentId = bundle.getString(AssessmentEntity.ASSESSMENT_ID);

        return view;
    }

    @Override
    public void onResume() {
        super.onResume();
        presenter.getApplicants(PaginationEntity.LIMIT, PaginationEntity.OFFSET, assessmentId, LSPUtils.getString(AsessorEntity.USER_ID));
    }

    @Override
    public void doFragmentInteraction() {

    }

    @Override
    public void doFragmentChange(Fragment fragment) {

    }

    @Override
    public void startActivity(Class c) {

    }

    @Override
    public void initViews() {
        llm = new LinearLayoutManager(getContext(), LinearLayoutManager.VERTICAL, false);
        recyclerView.setLayoutManager(llm);
    }

    @Override
    public void setApplicantsList(List<Applicant> applicantsList) {
        adapter = new ApplicantsAdapter(getContext(), applicantsList, assessmentId, ApplicantsAdapter.AssessmentStep.REAL_ASSESSMENT);
        recyclerView.setAdapter(adapter);

        adapter.setRecyclerViewListener((view, applicant, position) -> {
            currentApplicantPosition = position;
            presenter.updateStatusAssessment(applicant.getStatusRecomendation(), applicant.getDescriptionRecomendation(), assessmentId, applicant.getAssessmentApplicantId());
        });

        if (applicantsList.size() < 1) {
            recyclerView.setVisibility(View.GONE);
            emptyContainer.setVisibility(View.VISIBLE);
            imgEmpty.setImageResource(R.drawable.empty);
            txtEmpty.setText(R.string.empty_assessee);
        } else {
            recyclerView.setVisibility(View.VISIBLE);
            emptyContainer.setVisibility(View.GONE);
        }
    }

    @Override
    public void updateStatusRecomendation(String status) {
        adapter.getApplicantPosition(currentApplicantPosition).setStatusRecomendation(status);

        adapter.notifyDataSetChanged();
    }

    @Override
    public void onErrorResponse() {
        errorContainer.setVisibility(View.VISIBLE);
        recyclerView.setVisibility(View.GONE);
        emptyContainer.setVisibility(View.GONE);
        tryAgainButton.setOnClickListener(view -> presenter.getApplicants(PaginationEntity.LIMIT, PaginationEntity.OFFSET, assessmentId, LSPUtils.getString(AsessorEntity.USER_ID)));
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(getContext(), message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void showLoading() {
        ProgressLoadingBar.show(getContext());
    }

    @Override
    public void dismissLoading() {
        ProgressLoadingBar.dismiss();
    }

    @Override
    public void setNextPage(List<Applicant> applicants) {
        adapter.addApplicants(applicants);
    }

    @Override
    public void pagination() {
        recyclerView.addOnScrollListener(new PaginationRecyclerViewListener(llm, PaginationEntity.LIMIT) {
            @Override
            protected void loadMoreItems(int currentOffset) {
                presenter.loadNextPage(PaginationEntity.LIMIT, currentOffset, assessmentId, LSPUtils.getString(AsessorEntity.USER_ID));
            }
        });
    }

    @Override
    public void showLoadProgress() {
        loading.setVisibility(View.VISIBLE);
    }

    @Override
    public void dismissLoadProgress() {
        loading.setVisibility(View.GONE);
    }
}
