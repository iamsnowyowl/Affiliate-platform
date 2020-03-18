package com.aplikasisertifikasi.asesor.lspabi.Main.Fragment.Home;

import android.content.Intent;
import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.TextView;
import android.widget.Toast;

import com.aplikasisertifikasi.asesor.lspabi.Entity.StatusKegiatanEntity;
import com.nex3z.notificationbadge.NotificationBadge;
import com.aplikasisertifikasi.asesor.lspabi.Entity.PaginationEntity;
import com.aplikasisertifikasi.asesor.lspabi.Utils.Pagination.PaginationRecyclerViewListener;
import com.victor.loading.rotate.RotateLoading;

import butterknife.OnClick;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.AssessmentAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.RoleEntity;
import com.aplikasisertifikasi.asesor.lspabi.Main.Notification.Notifications;
import com.aplikasisertifikasi.asesor.lspabi.Model.AssessmentSchedule;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Preference.LSPUtils;
import com.aplikasisertifikasi.asesor.lspabi.R;

import java.util.ArrayList;
import java.util.List;

import butterknife.BindView;
import butterknife.ButterKnife;

public class Home extends Fragment implements HomeContract.View {
    @BindView(R.id.recyclerKegiatan)
    RecyclerView recyclerView;
    @BindView(R.id.not_have_task)
    LinearLayout layout_no_task;
    @BindView(R.id.notif_badge)
    NotificationBadge badge;
    @BindView(R.id.rotate_loading_home)
    RotateLoading rotateLoading;
    @BindView(R.id.img_empty_assessment)
    ImageView imgEmptyAssessment;
    @BindView(R.id.txt_empty_assessment)
    TextView txtEmptyAssessment;
    @BindView(R.id.error_container)
    LinearLayout errorContainer;
    @BindView(R.id.try_again_button)
    Button tryAgainButton;
    @BindView(R.id.next_page_loading)
    ProgressBar loading;

    LinearLayoutManager linearLayoutManager;
    AssessmentAdapter adapter;
    HomePresenter presenter = new HomePresenter(this);
    int badgeCount = 0;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
    }

    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {

        View view = inflater.inflate(R.layout.fragment_home, container, false);
        ButterKnife.bind(this, view);
        presenter.start();

        return view;
    }

    @Override
    public void onResume() {
        super.onResume();
//        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
//            presenter.getAssessmentManagement(PaginationEntity.LIMIT, PaginationEntity.OFFSET);
//        } else {
        presenter.getAssessmentList(PaginationEntity.LIMIT, PaginationEntity.OFFSET);
//        }
        presenter.getBadgeCount();
    }

    @OnClick(R.id.btnNotification)
    public void onNotifClick() {
        badgeCount = 0;
        badge.setNumber(badgeCount);
        Intent intent = new Intent(getContext(), Notifications.class);
        startActivity(intent);
    }

    @Override
    public void setAssessentList(List<AssessmentSchedule> assessentList) {
        recyclerView.setItemAnimator(new DefaultItemAnimator());
//        if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT)) {
//            adapter = new AssessmentAdapter(getActivity().getBaseContext(), assessentList, AssessmentAdapter.UserRoleType.MANAGEMENT);
//            recyclerView.setAdapter(adapter);
//        } else {
        ArrayList<AssessmentSchedule> newArray = new ArrayList<>();
        for (int i = 0; i < assessentList.size(); i++) {
            if(assessentList.get(i).getIsUserPleno() == 0){
                if(!assessentList.get(i).getLastStateSchedule().equals(StatusKegiatanEntity.PLENO_DOC_COMPLETED)
                        || assessentList.get(i).getLastStateSchedule().equals(StatusKegiatanEntity.PLENO_REPORT_READY)){
                    newArray.add(assessentList.get(i));
                }
            }else{
                newArray.add(assessentList.get(i));
            }
        }
        adapter = new AssessmentAdapter(getActivity().getBaseContext(), newArray, AssessmentAdapter.UserRoleType.ASSESSOR);
        recyclerView.setAdapter(adapter);
//        }

        if (newArray.size() < 1) {
            errorContainer.setVisibility(View.GONE);
            imgEmptyAssessment.setImageResource(R.drawable.empty);
            txtEmptyAssessment.setText(R.string.task);
            layout_no_task.setVisibility(View.VISIBLE);
            recyclerView.setVisibility(View.INVISIBLE);
        } else {
            errorContainer.setVisibility(View.GONE);
            layout_no_task.setVisibility(View.INVISIBLE);
            recyclerView.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public void setNextPageAssessment(List<AssessmentSchedule> assessmentScheduleList) {
        adapter.addAssessmentSchedules(assessmentScheduleList);
    }

    @Override
    public void setBadgeCount(NotificationModel notificationModel) {
        badgeCount = Integer.parseInt(notificationModel.getBadgeCount());

        badge.setNumber(badgeCount);
    }

    @Override
    public void showToast(String message) {
        Toast.makeText(getContext(), message, Toast.LENGTH_SHORT).show();
    }

    @Override
    public void showLoadProgress() {
        loading.setVisibility(View.VISIBLE);
    }

    @Override
    public void dismissLoadProgress() {
        loading.setVisibility(View.GONE);
    }

    @Override
    public void pagination() {
        recyclerView.addOnScrollListener(new PaginationRecyclerViewListener(linearLayoutManager, PaginationEntity.LIMIT) {
            @Override
            protected void loadMoreItems(int currentOffset) {
//                if (LSPUtils.getRoleCode().equals(RoleEntity.MANAGEMENT))
//                    presenter.loadNextManagementPage(PaginationEntity.LIMIT, currentOffset);
//                else
                presenter.loadNextAssessmentPage(PaginationEntity.LIMIT, currentOffset);
            }
        });
    }

    @Override
    public void startActivity(Class c) {

    }

    @Override
    public void initViews() {
        linearLayoutManager = new LinearLayoutManager(getActivity().getBaseContext(), LinearLayoutManager.VERTICAL, false);
        recyclerView.setLayoutManager(linearLayoutManager);
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
        layout_no_task.setVisibility(View.GONE);
        tryAgainButton.setOnClickListener(v -> {
            errorContainer.setVisibility(View.GONE);
            presenter.getAssessmentList(PaginationEntity.LIMIT, PaginationEntity.OFFSET);
        });
    }
}
