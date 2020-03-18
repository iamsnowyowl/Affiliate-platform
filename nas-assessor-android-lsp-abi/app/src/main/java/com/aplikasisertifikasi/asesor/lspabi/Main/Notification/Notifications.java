package com.aplikasisertifikasi.asesor.lspabi.Main.Notification;

import android.os.Bundle;
import android.support.v7.widget.DefaultItemAnimator;
import android.support.v7.widget.DividerItemDecoration;
import android.support.v7.widget.LinearLayoutManager;
import android.support.v7.widget.RecyclerView;
import android.view.View;
import android.widget.Button;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.ProgressBar;
import android.widget.RelativeLayout;
import android.widget.TextView;
import android.widget.Toast;

import org.greenrobot.eventbus.Subscribe;

import java.util.List;

import butterknife.BindView;

import com.aplikasisertifikasi.asesor.lspabi.Adapter.NotificationsAdapter;
import com.aplikasisertifikasi.asesor.lspabi.Entity.PaginationEntity;
import com.aplikasisertifikasi.asesor.lspabi.MVPCore.BaseActivity;
import com.aplikasisertifikasi.asesor.lspabi.Model.NotificationModel;
import com.aplikasisertifikasi.asesor.lspabi.Model.ResponseMessage;
import com.aplikasisertifikasi.asesor.lspabi.R;
import com.aplikasisertifikasi.asesor.lspabi.Utils.Pagination.PaginationRecyclerViewListener;
import com.aplikasisertifikasi.asesor.lspabi.Utils.ProgressLoadingBar;


public class Notifications extends BaseActivity implements NotificationContract.View {

    @BindView(R.id.recyclerNotification)
    RecyclerView recyclerView;
    @BindView(R.id.frame_notification)
    RelativeLayout frameLayout;
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
    LinearLayoutManager llm;

    NotificationsPresenter presenter = new NotificationsPresenter(this);
    NotificationsAdapter adapter;

    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        presenter.start();
    }

    @Subscribe
    public void onResponseEvent(ResponseMessage responseMessage) {

    }

    @Override
    protected void onResume() {
        super.onResume();
        presenter.getNotifications(PaginationEntity.LIMIT, PaginationEntity.OFFSET);
    }

    @Override
    protected int getLayoutId() {
        return R.layout.fragment_notifications;
    }

    @Override
    public void showLoadingView() {
        ProgressLoadingBar.show(this);
    }

    @Override
    public void dismissLoadingView() {
        ProgressLoadingBar.dismiss();
    }

    @Override
    public void errorLoadingView() {
        errorContainer.setVisibility(View.VISIBLE);
        recyclerView.setVisibility(View.GONE);
        emptyContainer.setVisibility(View.GONE);
        tryAgainButton.setOnClickListener(view -> presenter.getNotifications(PaginationEntity.LIMIT, PaginationEntity.OFFSET));
    }

    @Override
    public void startActivity(Class c) {

    }

    @Override
    public void initViews() {
        llm = new LinearLayoutManager(this, LinearLayoutManager.VERTICAL, false);
        recyclerView.setLayoutManager(llm);
    }

    @Override
    public void fetchNotifications(List<NotificationModel> notifications) {
        adapter = new NotificationsAdapter(this, notifications);
        recyclerView.setItemAnimator(new DefaultItemAnimator());
        recyclerView.addItemDecoration(new DividerItemDecoration(this, DividerItemDecoration.VERTICAL));
        recyclerView.setAdapter(adapter);

        if (notifications.size() < 1) {
            emptyContainer.setVisibility(View.VISIBLE);
            recyclerView.setVisibility(View.GONE);
            imgEmpty.setImageResource(R.drawable.empty);
            txtEmpty.setText(R.string.empty_notif);
        } else {
            emptyContainer.setVisibility(View.GONE);
            recyclerView.setVisibility(View.VISIBLE);
        }
    }

    @Override
    public void setNextPage(List<NotificationModel> notificationModels) {
        adapter.addNotifications(notificationModels);
    }

    @Override
    public void pagination() {
        recyclerView.addOnScrollListener(new PaginationRecyclerViewListener(llm, PaginationEntity.LIMIT) {
            @Override
            protected void loadMoreItems(int currentOffset) {
                presenter.loadNextPage(PaginationEntity.LIMIT, currentOffset);
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

    @Override
    public void showToast(String message) {
        Toast.makeText(this, message, Toast.LENGTH_SHORT).show();
    }
}